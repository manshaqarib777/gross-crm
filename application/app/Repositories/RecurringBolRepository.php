<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for recurring invocies
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Repositories;

use App\Models\Bol;
use App\Repositories\CloneBolRepository;
use App\Repositories\EmailerRepository;
use App\Repositories\EventRepository;
use App\Repositories\EventTrackingRepository;
use App\Repositories\BolGeneratorRepository;
use App\Repositories\UserRepository;
use Log;

class RecurringBolRepository {

    /**
     * Clone bol repository
     */
    protected $clonebolrepo;

    /**
     * bol model
     */
    protected $bolmodel;

    /**
     * The event tracking repository instance.
     */
    protected $trackingrepo;

    /**
     * The event repository instance.
     */
    protected $eventrepo;

    /**
     * The bol generator repository
     */
    protected $bolgenerator;

    /**
     * Inject dependecies
     */
    public function __construct(
        CloneBolRepository $clonebolrepo,
        Bol $bolmodel,
        EventRepository $eventrepo,
        EventTrackingRepository $trackingrepo,
        UserRepository $userrepo,
        EmailerRepository $emailerrepo,
        BolGeneratorRepository $bolgenerator
    ) {

        $this->clonebolrepo = $clonebolrepo;
        $this->bolmodel = $bolmodel;
        $this->eventrepo = $eventrepo;
        $this->trackingrepo = $trackingrepo;
        $this->emailerrepo = $emailerrepo;
        $this->userrepo = $userrepo;
        $this->bolgenerator = $bolgenerator;

    }

    /**
     * find all bols that need to be recurred today and recur them
     * @param numeric number of bold to get at a time. Set to 0 for unlimited
     * @return obj bol collection
     */
    public function processBols($limit = 1) {

        Log::info("recurring bol processing started", ['process' => '[recurring-bols-cronjob]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);

        //validate
        if (!is_numeric($limit)) {
            $limit = 1;
        }

        //do we have any bols
        if (!$bols = $this->getBols($limit)) {
            Log::info("no bols recurring today were found", ['process' => '[recurring-bols-cronjob]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return;
        }

        //bol
        Log::info("applicable bols were found - count (" . count($bols) . ")", ['process' => '[recurring-bols-cronjob]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);

        //recur each bol
        if (count($bols) > 0) {
            $this->recurBols($bols);
        }

        return $bols;
    }

    /**
     * find all bols that need to be recurred today, regardless billing cycle (daily, weekly etc)
     * @param numeric number of bold to get at a time. Set to 0 for unlimited
     * @return obj bol collection
     */
    private function getBols($limit) {

        //todays date
        $today = \Carbon\Carbon::now()->format('Y-m-d');

        Log::info("searching for bols that are due for renewal today ($today)", ['process' => '[recurring-bols-cronjob]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);


        //new query
        $bols = $this->bolmodel->newQuery();

        // all fields
        $bols->selectRaw('*');

        //next bill date is today
        $bols->where('bill_recurring_next', $today);

        //recurring bols
        $bols->where('bill_recurring', 'yes');

        //exlcude those already processings
        $bols->where('bill_cron_status', '!=', 'processing');

        //valid cycles
        $bols->where('bill_recurring_duration', '>', 0);

        //there is still billing cycles to go
        $bols->where(function ($query) {
            //infinite
            $query->where('bill_recurring_cycles', 0);
            //or still has cycles to go
            $query->orWhereColumn('bill_recurring_cycles_counter', '<', 'bill_recurring_cycles');
        });

        //exclude draft bols
        $bols->whereNotIn('bill_status', ['draft']);

        if ($limit == 0) {
            //return all rows
            return $bols->get();
        } else {
            //return all rows
            return $bols->take($limit)->get();
        }
    }

    /**
     * recur each bol
     * @param object $bols collection
     * @return object bol collection
     */
    private function recurBols($bols) {

        Log::info("starting to clone and recur the bols", ['process' => '[recurring-bols-cronjob]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);

        //mark all bols as processing, do avoid collisions
        foreach ($bols as $bol) {
            $bol->bill_cron_status = 'processing';
            $bol->bill_cron_date = now();
            $bol->save();
        }

        //clone bols one by one
        foreach ($bols as $bol) {

            //cloning data
            $data = [
                'bol_id' => $bol->bill_bolid,
                'client_id' => $bol->bill_clientid,
                'project_id' => $bol->bill_projectid,
                'bol_date' => now(),
                'bol_due_date' => now()->addDays(config('system.settings_bols_recurring_grace_period')),
                'return' => 'object',
            ];
            if (!$child_bol = $this->clonebolrepo->clone($data)) {
                //mark as error
                $bol->bill_cron_status = 'error';
                $bol->save();
                //skip rest of tasks & log error
                Log::critical("the parent bol could not cloned", ['process' => '[recurring-bols-cronjob]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'bol_id' => $bol->bill_bolid]);
                continue;
            }

            //[parent bol] update next due date
            switch ($bol->bill_recurring_period) {
            case 'day':
                $bol->bill_recurring_next = now()->addDays($bol->bill_recurring_duration);
                break;

            case 'week':
                $bol->bill_recurring_next = now()->addWeeks($bol->bill_recurring_duration);
                break;

            case 'month':
                $bol->bill_recurring_next = now()->addMonthsNoOverflow($bol->bill_recurring_duration);
                break;

            case 'year':
                $bol->bill_recurring_next = now()->addYearsNoOverflow($bol->bill_recurring_duration);
                break;
            }

            //[parent bol] update when last it was last recured (todays date)
            $bol->bill_recurring_last = now();

            //[parent bol] update cycles counter
            $bol->bill_recurring_cycles_counter = $bol->bill_recurring_cycles_counter + 1;
  
            //save parent bol
            $bol->save();

            //[child bol] updates
            $child_bol->bill_recurring_child = 'yes';
            $child_bol->bill_recurring_parent_id = $bol->bill_bolid;
            $child_bol->bill_status = 'due';
            $child_bol->save();

            //publish the new bol & create timeline events
            $this->publishBol($child_bol->bill_bolid, $bol);

        }
    }

    /**
     * publish the new bol. It will actually be added to email queue, which will processed by another cronjob
     * create timeline evemts and notifications
     * @param int $bol_id bol id
     * @param object $parent parent bol
     * @return bool
     */
    private function publishBol($bol_id, $parent) {

        Log::info("publishing new bol - started", ['process' => '[recurring-bols-cronjob]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'bol_id' => $bol_id]);

        //get the generated bol
        if (!$payload = $this->bolgenerator->generate($bol_id)) {
            Log::critical("publishing new bol failed - bol could not be generated", ['process' => '[recurring-bols-cronjob]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'bol_id' => $bol_id]);
            return false;
        }

        //bol
        $bol = $payload['bill'];

        /** ----------------------------------------------
         * record event [comment]
         * ----------------------------------------------*/
        $resource_id = (is_numeric($bol->bill_projectid)) ? $bol->bill_projectid : $bol->bill_clientid;
        $resource_type = (is_numeric($bol->bill_projectid)) ? 'project' : 'client';
        $data = [
            'event_creatorid' => 0, //created by 'system' user
            'event_item' => 'bol',
            'event_item_id' => $bol->bill_bolid,
            'event_item_lang' => 'event_created_bol',
            'event_item_content' => __('lang.bol') . ' - ' . $bol->formatted_bill_bolid,
            'event_item_content2' => '',
            'event_parent_type' => 'bol',
            'event_parent_id' => $bol->bill_bolid,
            'event_parent_title' => $bol->project_title,
            'event_clientid' => $bol->bill_clientid,
            'event_show_item' => 'yes',
            'event_show_in_timeline' => 'yes',
            'eventresource_type' => $resource_type,
            'eventresource_id' => $resource_id,
            'event_notification_category' => 'notifications_billing_activity',
        ];
        //record event
        if ($event_id = $this->eventrepo->create($data)) {
            //get users (main client)
            $users = $this->userrepo->getClientUsers($bol->bill_clientid, 'owner', 'ids');
            //record notification
            $emailusers = $this->trackingrepo->recordEvent($data, $users, $event_id);
        }

        /** ----------------------------------------------
         * send email [queued]
         * ----------------------------------------------*/
        if (isset($emailusers) && is_array($emailusers)) {
            //other data
            $data = [];
            //send to client users
            if ($users = \App\Models\User::WhereIn('id', $emailusers)->get()) {
                foreach ($users as $user) {
                    $mail = new \App\Mail\PublishBol($user, $data, $bol);
                    $mail->build();
                }
            }
        }

        //[parent bol] update cron status
        $parent->bill_cron_status = 'completed';
        $parent->bill_cron_date = now();
        $parent->save();

        Log::info("publishing new bol - completed", ['process' => '[recurring-bols-cronjob]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'bol_id' => $bol_id]);

        return true;
    }

}