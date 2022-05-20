<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for cloning quotes
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

/** --------------------------------------------------------------------------
 * [Clone Quote Repository]
 * Clone an quote. The new quote is set to 'draft status' by default
 * It can be published as needed
 * @source Nextloop
 *--------------------------------------------------------------------------*/
namespace App\Repositories;

use App\Repositories\QuoteGeneratorRepository;
use App\Repositories\QuoteRepository;
use Log;

class CloneQuoteRepository {

    /**
     * The quote repo instance
     */
    protected $quoterepo;

    /**
     * The quote generator instance
     */
    protected $quotegenerator;

    /**
     * Inject dependecies
     */
    public function __construct(QuoteRepository $quoterepo, QuoteGeneratorRepository $quotegenerator) {
        $this->quoterepo = $quoterepo;
        $this->quotegenerator = $quotegenerator;
    }

    /**
     * Clone an quote
     * @param array data array
     *              - quote_id
     *              - client_id
     *              - project_id
     *              - quote_date
     *              - quote_due_date
     *              - return (id|object)
     * @return mixed int|object
     */
    public function clone ($data = []) {

        //info
        Log::info("cloning quote started", ['process' => '[CloneQuoteRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'payload' => $data]);

        //validate information
        if (!$this->validateData($data)) {
            Log::info("cloning quote failed", ['process' => '[CloneQuoteRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'payload' => $data]);
            return false;
        }

        //get the quote via the quote generator
        if (!$payload = $this->quotegenerator->generate($data['quote_id'])) {
            Log::error("an quote with this quote id, could not be loaded", ['process' => '[CloneQuoteRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'quote_id' => $data['quote_id']]);
            return false;
        }

        //get clean quote object for cloning
        $quote = \App\Models\Quote::Where('bill_quoteid', $data['quote_id'])->first();

        //clone main quote
        $new_quote = $quote->replicate();

        //update new quote with specified data
        $new_quote->bill_clientid = $data['client_id'];
        $new_quote->bill_date = $data['quote_date'];
        $new_quote->bill_created = now();
        $new_quote->bill_updated = now();
        $new_quote->bill_due_date = $data['quote_due_date'];
        $new_quote->bill_projectid = $data['project_id'];
        $new_quote->bill_visibility = 'hidden';
        $new_quote->bill_status = 'draft';

        //[cleanup] remove recurring and other unwanted data, inherited from parent
        $new_quote->bill_recurring = 'no';
        $new_quote->bill_recurring_duration = null;
        $new_quote->bill_recurring_period = null;
        $new_quote->bill_recurring_cycles = null;
        $new_quote->bill_recurring_cycles_counter = null;
        $new_quote->bill_recurring_last = null;
        $new_quote->bill_recurring_next = null;
        $new_quote->bill_recurring_child = 'no';
        $new_quote->bill_recurring_parent_id = null;
        $new_quote->bill_overdue_reminder_sent = 'no';
        $new_quote->bill_date_sent_to_customer = null;
        $new_quote->bill_notes = '';
        $new_quote->bill_cron_status = 'none';
        $new_quote->bill_cron_date = null;

        //save
        $new_quote->save();

        //replicate each line item
        foreach ($payload['lineitems'] as $lineitem_x) {

            //get clean lineitem object for cloning
            if (!$lineitem = \App\Models\LineItem::Where('lineitem_id', $lineitem_x->lineitem_id)->first()) {
                //skip it
                $continue;
            }

            //clone line
            $new_lineitem = $lineitem->replicate();
            $new_lineitem->lineitemresource_id = $new_quote->bill_quoteid;
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

        //replicate main quotes tax items
        foreach ($payload['taxes'] as $tax) {
            //get clean tax item for cloning
            if ($tax = \App\Models\Tax::Where('tax_id', $tax_x->tax_id)->first()) {
                if ($tax->taxresource_type == 'quote') {
                    $new_tax = $tax->replicate();
                    $new_tax->taxresource_id = $new_quote->bill_quoteid;
                    $new_tax->save();
                }
            }
        }

        //finished, make quote visible
        $new_quote->bill_visibility = 'visible';
        $new_quote->save();


        Log::info("cloning quote completed", ['process' => '[CloneQuoteRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'new_quote_id' => $new_quote->bill_quoteid]);

        //return quote id | quote object
        if (isset($data['return']) && $data['return'] == 'id') {
            return $new_quote->bill_quoteid;
        } else {
            return $new_quote;
        }
    }

    /**
     * validate required data for cloning an quote
     * @param array $data information payload
     * @return bool
     */
    private function validateData($data = []) {

        //validation
        if (!isset($data['quote_id']) || !isset($data['client_id']) || !isset($data['quote_date']) || !isset($data['quote_due_date'])) {
            Log::error("the supplied data is not valid", ['process' => '[CloneQuoteRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'payload' => $data]);
            return false;
        }

        //quote id
        if (!is_numeric($data['quote_id'])) {
            Log::error("the supplied quote id is invalid", ['process' => '[CloneQuoteRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'quote_id' => $data['quote_id']]);
            return false;
        }

        //client id
        if (!is_numeric($data['client_id'])) {
            Log::error("the supplied client id is invalid", ['process' => '[CloneQuoteRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'client_id' => $data['client_id']]);
            return false;
        }

        //check client exists
        if (!$client = \App\Models\Client::Where('client_id', $data['client_id'])->first()) {
            Log::error("the specified client could not be found", ['process' => '[CloneQuoteRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'project_id' => $data['project_id']]);
            return false;
        }

        //check project exists
        if (isset($data['project_id']) && is_numeric($data['project_id'])) {
            if (!$project = \App\Models\Project::Where('project_id', $data['project_id'])->first()) {
                Log::error("the specified project could not be found", ['process' => '[CloneQuoteRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'project_id' => $data['project_id']]);
                return false;
            }
        }

        //check client and project match
        if (isset($data['project_id']) && is_numeric($data['project_id'])) {
            if ($project->project_clientid != $client->client_id) {
                Log::error("the specified client & project do not match", ['process' => '[CloneQuoteRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'project_id' => $data['project_id']]);
                return false;
            }
        }

        return true;
    }

}