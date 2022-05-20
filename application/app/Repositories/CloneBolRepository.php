<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for cloning bols
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

/** --------------------------------------------------------------------------
 * [Clone Bol Repository]
 * Clone an bol. The new bol is set to 'draft status' by default
 * It can be published as needed
 * @source Nextloop
 *--------------------------------------------------------------------------*/
namespace App\Repositories;

use App\Repositories\BolGeneratorRepository;
use App\Repositories\BolRepository;
use Log;

class CloneBolRepository {

    /**
     * The bol repo instance
     */
    protected $bolrepo;

    /**
     * The bol generator instance
     */
    protected $bolgenerator;

    /**
     * Inject dependecies
     */
    public function __construct(BolRepository $bolrepo, BolGeneratorRepository $bolgenerator) {
        $this->bolrepo = $bolrepo;
        $this->bolgenerator = $bolgenerator;
    }

    /**
     * Clone an bol
     * @param array data array
     *              - bol_id
     *              - client_id
     *              - project_id
     *              - bol_date
     *              - bol_due_date
     *              - return (id|object)
     * @return mixed int|object
     */
    public function clone ($data = []) {

        //info
        Log::info("cloning bol started", ['process' => '[CloneBolRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'payload' => $data]);

        //validate information
        if (!$this->validateData($data)) {
            Log::info("cloning bol failed", ['process' => '[CloneBolRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'payload' => $data]);
            return false;
        }

        //get the bol via the bol generator
        if (!$payload = $this->bolgenerator->generate($data['bol_id'])) {
            Log::error("an bol with this bol id, could not be loaded", ['process' => '[CloneBolRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'bol_id' => $data['bol_id']]);
            return false;
        }

        //get clean bol object for cloning
        $bol = \App\Models\Bol::Where('bill_bolid', $data['bol_id'])->first();

        //clone main bol
        $new_bol = $bol->replicate();

        //update new bol with specified data
        $new_bol->bill_clientid = $data['client_id'];
        $new_bol->bill_date = $data['bol_date'];
        $new_bol->bill_created = now();
        $new_bol->bill_updated = now();
        $new_bol->bill_due_date = $data['bol_due_date'];
        $new_bol->bill_projectid = $data['project_id'];
        $new_bol->bill_visibility = 'hidden';
        $new_bol->bill_status = 'draft';

        //[cleanup] remove recurring and other unwanted data, inherited from parent
        $new_bol->bill_recurring = 'no';
        $new_bol->bill_recurring_duration = null;
        $new_bol->bill_recurring_period = null;
        $new_bol->bill_recurring_cycles = null;
        $new_bol->bill_recurring_cycles_counter = null;
        $new_bol->bill_recurring_last = null;
        $new_bol->bill_recurring_next = null;
        $new_bol->bill_recurring_child = 'no';
        $new_bol->bill_recurring_parent_id = null;
        $new_bol->bill_overdue_reminder_sent = 'no';
        $new_bol->bill_date_sent_to_customer = null;
        $new_bol->bill_notes = '';
        $new_bol->bill_cron_status = 'none';
        $new_bol->bill_cron_date = null;

        //save
        $new_bol->save();

        //replicate each line item
        foreach ($payload['lineitems'] as $lineitem_x) {

            //get clean lineitem object for cloning
            if (!$lineitem = \App\Models\LineItem::Where('lineitem_id', $lineitem_x->lineitem_id)->first()) {
                //skip it
                $continue;
            }

            //clone line
            $new_lineitem = $lineitem->replicate();
            $new_lineitem->lineitemresource_id = $new_bol->bill_bolid;
            $new_lineitem->save();

            //clone line tax rates
            foreach ($payload['taxes'] as $tax_x) {
                //get clean tax item for cloning
                if ($tax = \App\Models\Tax::Where('tax_id', $tax_x->tax_id)->first()) {
                    if ($tax->taxresource_type == 'lineitem' && $tax->taxresource_id == $lineitem->lineitem_id) {
                        $new_tax = $tax->replicate();
                        $new_tax->taxresource_id = $new_lineitem->lineitem_id;
                        $new_tax->save();
                    }
                }
            }
        }

        //replicate main bols tax items
        foreach ($payload['taxes'] as $tax) {
            //get clean tax item for cloning
            if ($tax = \App\Models\Tax::Where('tax_id', $tax_x->tax_id)->first()) {
                if ($tax->taxresource_type == 'bol') {
                    $new_tax = $tax->replicate();
                    $new_tax->taxresource_id = $new_bol->bill_bolid;
                    $new_tax->save();
                }
            }
        }

        //finished, make bol visible
        $new_bol->bill_visibility = 'visible';
        $new_bol->save();


        Log::info("cloning bol completed", ['process' => '[CloneBolRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'new_bol_id' => $new_bol->bill_bolid]);

        //return bol id | bol object
        if (isset($data['return']) && $data['return'] == 'id') {
            return $new_bol->bill_bolid;
        } else {
            return $new_bol;
        }
    }

    /**
     * validate required data for cloning an bol
     * @param array $data information payload
     * @return bool
     */
    private function validateData($data = []) {

        //validation
        if (!isset($data['bol_id']) || !isset($data['client_id']) || !isset($data['bol_date']) || !isset($data['bol_due_date'])) {
            Log::error("the supplied data is not valid", ['process' => '[CloneBolRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'payload' => $data]);
            return false;
        }

        //bol id
        if (!is_numeric($data['bol_id'])) {
            Log::error("the supplied bol id is invalid", ['process' => '[CloneBolRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'bol_id' => $data['bol_id']]);
            return false;
        }

        //client id
        if (!is_numeric($data['client_id'])) {
            Log::error("the supplied client id is invalid", ['process' => '[CloneBolRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'client_id' => $data['client_id']]);
            return false;
        }

        //check client exists
        if (!$client = \App\Models\Client::Where('client_id', $data['client_id'])->first()) {
            Log::error("the specified client could not be found", ['process' => '[CloneBolRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'project_id' => $data['project_id']]);
            return false;
        }

        //check project exists
        if (isset($data['project_id']) && is_numeric($data['project_id'])) {
            if (!$project = \App\Models\Project::Where('project_id', $data['project_id'])->first()) {
                Log::error("the specified project could not be found", ['process' => '[CloneBolRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'project_id' => $data['project_id']]);
                return false;
            }
        }

        //check client and project match
        if (isset($data['project_id']) && is_numeric($data['project_id'])) {
            if ($project->project_clientid != $client->client_id) {
                Log::error("the specified client & project do not match", ['process' => '[CloneBolRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'project_id' => $data['project_id']]);
                return false;
            }
        }

        return true;
    }

}