<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for recurring invocies
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Repositories;

use App\Models\Quote;
use App\Repositories\CloneQuoteRepository;
use App\Repositories\EmailerRepository;
use App\Repositories\EventRepository;
use App\Repositories\EventTrackingRepository;
use App\Repositories\QuoteGeneratorRepository;
use App\Repositories\UserRepository;
use Log;

class RecurringQuoteRepository {

    /**
     * Clone quote repository
     */
    protected $clonequoterepo;

    /**
     * quote model
     */
    protected $quotemodel;

    /**
     * The event tracking repository instance.
     */
    protected $trackingrepo;

    /**
     * The event repository instance.
     */
    protected $eventrepo;

    /**
     * The quote generator repository
     */
    protected $quotegenerator;

    /**
     * Inject dependecies
     */
    public function __construct(
        CloneQuoteRepository $clonequoterepo,
        Quote $quotemodel,
        EventRepository $eventrepo,
        EventTrackingRepository $trackingrepo,
        UserRepository $userrepo,
        EmailerRepository $emailerrepo,
        QuoteGeneratorRepository $quotegenerator
    ) {

        $this->clonequoterepo = $clonequoterepo;
        $this->quotemodel = $quotemodel;
        $this->eventrepo = $eventrepo;
        $this->trackingrepo = $trackingrepo;
        $this->emailerrepo = $emailerrepo;
        $this->userrepo = $userrepo;
        $this->quotegenerator = $quotegenerator;

    }

    /**
     * find all quotes that need to be recurred today and recur them
     * @param numeric number of quoted to get at a time. Set to 0 for unlimited
     * @return obj quote collection
     */
    public function processQuotes($limit = 1) {

        Log::info("recurring quote processing started", ['process' => '[recurring-quotes-cronjob]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);

        //validate
        if (!is_numeric($limit)) {
            $limit = 1;
        }

        //do we have any quotes
        if (!$quotes = $this->getQuotes($limit)) {
            Log::info("no quotes recurring today were found", ['process' => '[recurring-quotes-cronjob]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return;
        }

        //quote
        Log::info("applicable quotes were found - count (" . count($quotes) . ")", ['process' => '[recurring-quotes-cronjob]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);

        //recur each quote
        if (count($quotes) > 0) {
            $this->recurQuotes($quotes);
        }

        return $quotes;
    }

    /**
     * find all quotes that need to be recurred today, regardless billing cycle (daily, weekly etc)
     * @param numeric number of quoted to get at a time. Set to 0 for unlimited
     * @return obj quote collection
     */
    private function getQuotes($limit) {

        //todays date
        $today = \Carbon\Carbon::now()->format('Y-m-d');

        Log::info("searching for quotes that are due for renewal today ($today)", ['process' => '[recurring-quotes-cronjob]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);


        //new query
        $quotes = $this->quotemodel->newQuery();

        // all fields
        $quotes->selectRaw('*');

        //next bill date is today
        $quotes->where('bill_recurring_next', $today);

        //recurring quotes
        $quotes->where('bill_recurring', 'yes');

        //exlcude those already processings
        $quotes->where('bill_cron_status', '!=', 'processing');

        //valid cycles
        $quotes->where('bill_recurring_duration', '>', 0);

        //there is still billing cycles to go
        $quotes->where(function ($query) {
            //infinite
            $query->where('bill_recurring_cycles', 0);
            //or still has cycles to go
            $query->orWhereColumn('bill_recurring_cycles_counter', '<', 'bill_recurring_cycles');
        });

        //exclude draft quotes
        $quotes->whereNotIn('bill_status', ['draft']);

        if ($limit == 0) {
            //return all rows
            return $quotes->get();
        } else {
            //return all rows
            return $quotes->take($limit)->get();
        }
    }

    /**
     * recur each quote
     * @param object $quotes collection
     * @return object quote collection
     */
    private function recurQuotes($quotes) {

        Log::info("starting to clone and recur the quotes", ['process' => '[recurring-quotes-cronjob]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);

        //mark all quotes as processing, do avoid collisions
        foreach ($quotes as $quote) {
            $quote->bill_cron_status = 'processing';
            $quote->bill_cron_date = now();
            $quote->save();
        }

        //clone quotes one by one
        foreach ($quotes as $quote) {

            //cloning data
            $data = [
                'quote_id' => $quote->bill_quoteid,
                'client_id' => $quote->bill_clientid,
                'project_id' => $quote->bill_projectid,
                'quote_date' => now(),
                'quote_due_date' => now()->addDays(config('system.settings_quotes_recurring_grace_period')),
                'return' => 'object',
            ];
            if (!$child_quote = $this->clonequoterepo->clone($data)) {
                //mark as error
                $quote->bill_cron_status = 'error';
                $quote->save();
                //skip rest of tasks & log error
                Log::critical("the parent quote could not cloned", ['process' => '[recurring-quotes-cronjob]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'quote_id' => $quote->bill_quoteid]);
                continue;
            }

            //[parent quote] update next due date
            switch ($quote->bill_recurring_period) {
            case 'day':
                $quote->bill_recurring_next = now()->addDays($quote->bill_recurring_duration);
                break;

            case 'week':
                $quote->bill_recurring_next = now()->addWeeks($quote->bill_recurring_duration);
                break;

            case 'month':
                $quote->bill_recurring_next = now()->addMonthsNoOverflow($quote->bill_recurring_duration);
                break;

            case 'year':
                $quote->bill_recurring_next = now()->addYearsNoOverflow($quote->bill_recurring_duration);
                break;
            }

            //[parent quote] update when last it was last recured (todays date)
            $quote->bill_recurring_last = now();

            //[parent quote] update cycles counter
            $quote->bill_recurring_cycles_counter = $quote->bill_recurring_cycles_counter + 1;
  
            //save parent quote
            $quote->save();

            //[child quote] updates
            $child_quote->bill_recurring_child = 'yes';
            $child_quote->bill_recurring_parent_id = $quote->bill_quoteid;
            $child_quote->bill_status = 'due';
            $child_quote->save();

            //publish the new quote & create timeline events
            $this->publishQuote($child_quote->bill_quoteid, $quote);

        }
    }

    /**
     * publish the new quote. It will actually be added to email queue, which will processed by another cronjob
     * create timeline evemts and notifications
     * @param int $quote_id quote id
     * @param object $parent parent quote
     * @return bool
     */
    private function publishQuote($quote_id, $parent) {

        Log::info("publishing new quote - started", ['process' => '[recurring-quotes-cronjob]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'quote_id' => $quote_id]);

        //get the generated quote
        if (!$payload = $this->quotegenerator->generate($quote_id)) {
            Log::critical("publishing new quote failed - quote could not be generated", ['process' => '[recurring-quotes-cronjob]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'quote_id' => $quote_id]);
            return false;
        }

        //quote
        $quote = $payload['bill'];

        /** ----------------------------------------------
         * record event [comment]
         * ----------------------------------------------*/
        $resource_id = (is_numeric($quote->bill_projectid)) ? $quote->bill_projectid : $quote->bill_clientid;
        $resource_type = (is_numeric($quote->bill_projectid)) ? 'project' : 'client';
        $data = [
            'event_creatorid' => 0, //created by 'system' user
            'event_item' => 'quote',
            'event_item_id' => $quote->bill_quoteid,
            'event_item_lang' => 'event_created_quote',
            'event_item_content' => __('lang.quote') . ' - ' . $quote->formatted_bill_quoteid,
            'event_item_content2' => '',
            'event_parent_type' => 'quote',
            'event_parent_id' => $quote->bill_quoteid,
            'event_parent_title' => $quote->project_title,
            'event_clientid' => $quote->bill_clientid,
            'event_show_item' => 'yes',
            'event_show_in_timeline' => 'yes',
            'eventresource_type' => $resource_type,
            'eventresource_id' => $resource_id,
            'event_notification_category' => 'notifications_billing_activity',
        ];
        //record event
        if ($event_id = $this->eventrepo->create($data)) {
            //get users (main client)
            $users = $this->userrepo->getClientUsers($quote->bill_clientid, 'owner', 'ids');
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
                    $mail = new \App\Mail\PublishQuote($user, $data, $quote);
                    $mail->build();
                }
            }
        }

        //[parent quote] update cron status
        $parent->bill_cron_status = 'completed';
        $parent->bill_cron_date = now();
        $parent->save();

        Log::info("publishing new quote - completed", ['process' => '[recurring-quotes-cronjob]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'quote_id' => $quote_id]);

        return true;
    }

}