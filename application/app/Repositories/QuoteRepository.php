<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for quotes
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Repositories;

use App\Models\Quote;
use App\Repositories\LineitemRepository;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Log;

class QuoteRepository {

    /**
     * The quotes repository instance.
     */
    protected $quotes;

    /**
     * Inject dependecies
     */
    public function __construct(Quote $quotes, LineitemRepository $lineitemrepo) {
        $this->quotes = $quotes;
        $this->lineitemrepo = $lineitemrepo;
    }

    /**
     * Search model
     * @param int $id optional for getting a single, specified record
     * @param array $data optional data payload
     * @return object quote collection
     */
    public function search($id = '', $data = array()) {

        $quotes = $this->quotes->newQuery();

        //joins
        $quotes->leftJoin('clients', 'clients.client_id', '=', 'quotes.bill_clientid');
        $quotes->leftJoin('projects', 'projects.project_id', '=', 'quotes.bill_projectid');
        $quotes->leftJoin('categories', 'categories.category_id', '=', 'quotes.bill_categoryid');
        $quotes->leftJoin('users', 'users.id', '=', 'quotes.bill_creatorid');

        //join: users reminders - do not do this for cronjobs
        if (auth()->check()) {
            $quotes->leftJoin('reminders', function ($join) {
                $join->on('reminders.reminderresource_id', '=', 'quotes.bill_quoteid')
                    ->where('reminders.reminderresource_type', '=', 'quote')
                    ->where('reminders.reminder_userid', '=', auth()->id());
            });
        }

        // all fields
        $quotes->selectRaw('*');

        //count payments
        $quotes->selectRaw('(SELECT COUNT(*)
                                      FROM payments
                                      WHERE payment_quoteid = quotes.bill_quoteid)
                                      AS count_payments');

        //sum payments
        $quotes->selectRaw('(SELECT COALESCE(SUM(payment_amount), 0)
                                      FROM payments WHERE payment_quoteid = quotes.bill_quoteid
                                      GROUP BY payment_quoteid)
                                      AS x_sum_payments');
        $quotes->selectRaw('(SELECT COALESCE(x_sum_payments, 0.00))
                                      AS sum_payments');

        //quote balance
        $quotes->selectRaw('(SELECT COALESCE(bill_final_amount - sum_payments, 0.00))
                                      AS quote_balance');

        //default where
        $quotes->whereRaw("1 = 1");

        //filters: id
        if (request()->filled('filter_bill_quoteid')) {
            $quotes->where('bill_quoteid', request('filter_bill_quoteid'));
        }
        if (is_numeric($id)) {
            $quotes->where('bill_quoteid', $id);
        }

        //filter by subscription id
        if (isset($data['bill_subscriptionid'])) {
            $quotes->where('bill_subscriptionid', $data['bill_subscriptionid']);
        }

        //do not show items that not yet ready (i.e exclude items in the process of being cloned that have status 'invisible')
        $quotes->where('bill_visibility', 'visible');

        //filter clients
        if (request()->filled('filter_bill_clientid')) {
            $quotes->where('bill_clientid', request('filter_bill_clientid'));
        }

        //filter projects
        if (request()->filled('filter_bill_projectid')) {
            $quotes->where('bill_projectid', request('filter_bill_projectid'));
        }

        //filter: amount (min)
        if (request()->filled('filter_bill_final_amount_min')) {
            $quotes->where('bill_final_amount', '>=', request('filter_bill_final_amount_min'));
        }

        //filter: amount (max)
        if (request()->filled('filter_bill_final_amount_max')) {
            $quotes->where('bill_final_amount', '<=', request('filter_bill_final_amount_max'));
        }

        //filter: payments (max)
        if (request()->filled('filter_quote_payments_max')) {
            $quotes->where('sum_payments', '>=', request('filter_quote_payments_max'));
        }

        //filter: quote (start)
        if (request()->filled('filter_bill_date_start')) {
            $quotes->where('bill_date', '>=', request('filter_bill_date_start'));
        }

        //filter: quote (end)
        if (request()->filled('filter_bill_date_end')) {
            $quotes->where('bill_date', '<=', request('filter_bill_date_end'));
        }

        //filter: quote (start)
        if (request()->filled('filter_bill_due_date_start')) {
            $quotes->whereDate('bill_due_date', '>=', request('filter_bill_due_date_start'));
        }

        //filter: quote (end)
        if (request()->filled('filter_bill_due_date_end')) {
            $quotes->whereDate('bill_due_date', '<=', request('filter_bill_due_date_end'));
        }


        //filter by pickup location
        if (isset($data['pickup_location'])) {
            $quotes->where('pickup_location', $data['pickup_location']);
        }




        //filter by contact_mc_dot_number
        if (isset($data['contact_mc_dot_number'])) {
            $quotes->where('contact_mc_dot_number', $data['contact_mc_dot_number']);
        }

        //filter by contact_name
        if (isset($data['contact_name'])) {
            $quotes->where('contact_name', $data['contact_name']);
        }

        //filter by contact_phone
        if (isset($data['contact_phone'])) {
            $quotes->where('contact_phone', $data['contact_phone']);
        }

        //filter by contact_term
        if (isset($data['contact_term'])) {
            $quotes->where('contact_term', $data['contact_term']);
        }

        //filter by contact_fax
        if (isset($data['contact_fax'])) {
            $quotes->where('contact_fax', $data['contact_fax']);
        }

        //filter by contact_address
        if (isset($data['contact_address'])) {
            $quotes->where('contact_address', $data['contact_address']);
        }
        //filter by contact_dispatcher
        if (isset($data['contact_dispatcher'])) {
            $quotes->where('contact_dispatcher', $data['contact_dispatcher']);
        }

        //filter by contact_driver
        if (isset($data['contact_driver'])) {
            $quotes->where('contact_driver', $data['contact_driver']);
        }

        //filter by contact_truck
        if (isset($data['contact_truck'])) {
            $quotes->where('contact_truck', $data['contact_truck']);
        }

        //filter by contact_trailer
        if (isset($data['contact_trailer'])) {
            $quotes->where('contact_trailer', $data['contact_trailer']);
        }

        //filter by load_mode
        if (isset($data['load_mode'])) {
            $quotes->where('load_mode', $data['load_mode']);
        }

        //filter by load_trailer_type
        if (isset($data['load_trailer_type'])) {
            $quotes->where('load_trailer_type', $data['load_trailer_type']);
        }

        //filter by load_trailer_size
        if (isset($data['load_trailer_size'])) {
            $quotes->where('load_trailer_size', $data['load_trailer_size']);
        }

        //filter by load_linear_feet
        if (isset($data['load_linear_feet'])) {
            $quotes->where('load_linear_feet', $data['load_linear_feet']);
        }

        //filter by load_temperature
        if (isset($data['load_temperature'])) {
            $quotes->where('load_temperature', $data['load_temperature']);
        }

        //filter by load_pallet_case_count
        if (isset($data['load_pallet_case_count'])) {
            $quotes->where('load_pallet_case_count', $data['load_pallet_case_count']);
        }

        //filter by load_hazmat
        if (isset($data['load_hazmat'])) {
            $quotes->where('load_hazmat', $data['load_hazmat']);
        }

        //filter by load_requirements
        if (isset($data['load_requirements'])) {
            $quotes->where('load_requirements', $data['load_requirements']);
        }

        //filter by load_instructions
        if (isset($data['load_instructions'])) {
            $quotes->where('load_instructions', $data['load_instructions']);
        }

        //filter by load_length
        if (isset($data['load_length'])) {
            $quotes->where('load_length', $data['load_length']);
        }

        //filter by load_width
        if (isset($data['load_width'])) {
            $quotes->where('load_width', $data['load_width']);
        }

        //filter by load_height
        if (isset($data['load_height'])) {
            $quotes->where('load_height', $data['load_height']);
        }


        //filter by pickup_phone
        if (isset($data['pickup_phone'])) {
            $quotes->where('pickup_phone', $data['pickup_phone']);
        }

        //filter by delivery_phone
        if (isset($data['delivery_phone'])) {
            $quotes->where('delivery_phone', $data['delivery_phone']);
        }

        //filter by carrier_unloading
        if (isset($data['carrier_unloading'])) {
            $quotes->where('carrier_unloading', $data['carrier_unloading']);
        }

        //filter by carrier_pallet_exchange
        if (isset($data['carrier_pallet_exchange'])) {
            $quotes->where('carrier_pallet_exchange', $data['carrier_pallet_exchange']);
        }

        //filter by carrier_estimated_weight
        if (isset($data['carrier_estimated_weight'])) {
            $quotes->where('carrier_estimated_weight', $data['carrier_estimated_weight']);
        }



        //filter by pickup telefax
        if (isset($data['pickup_telefax'])) {
            $quotes->where('pickup_telefax', $data['pickup_telefax']);
        }            
        
        //filter by pickup phone
        if (isset($data['pickup_phone'])) {
            $quotes->where('pickup_phone', $data['pickup_phone']);
        }            
        
        //filter by pickup email
        if (isset($data['pickup_email'])) {
            $quotes->where('pickup_email', $data['pickup_email']);
        }            
        
        //filter by pickup gstin
        if (isset($data['pickup_gstin'])) {
            $quotes->where('pickup_gstin', $data['pickup_gstin']);
        }


        //filter by delivery location
        if (isset($data['delivery_location'])) {
            $quotes->where('delivery_location', $data['delivery_location']);
        }

        //filter by delivery telefax
        if (isset($data['delivery_telefax'])) {
            $quotes->where('delivery_telefax', $data['delivery_telefax']);
        }            
        
        //filter by delivery phone
        if (isset($data['delivery_phone'])) {
            $quotes->where('delivery_phone', $data['delivery_phone']);
        }            
        
        //filter by delivery email
        if (isset($data['delivery_email'])) {
            $quotes->where('delivery_email', $data['delivery_email']);
        }            
        
        //filter by delivery gstin
        if (isset($data['delivery_gstin'])) {
            $quotes->where('delivery_gstin', $data['delivery_location']);
        }

        //filter by contact person
        if (isset($data['contact_person'])) {
            $quotes->where('contact_person', $data['contact_person']);
        }

        //filter by contact details
        if (isset($data['contact_details'])) {
            $quotes->where('contact_details', $data['contact_details']);
        }

        //filter by cargo commodity
        if (isset($data['cargo_commodity'])) {
            $quotes->where('cargo_commodity', $data['cargo_commodity']);
        }

        //filter by cargo weight
        if (isset($data['cargo_weight'])) {
            $quotes->where('cargo_weight', $data['cargo_weight']);
        }



        //resource filtering
        if (request()->filled('quoteresource_type') && request()->filled('quoteresource_id')) {
            switch (request('quoteresource_type')) {
            case 'client':
                $quotes->where('bill_clientid', request('quoteresource_id'));
                break;
            case 'project':
                $quotes->where('bill_projectid', request('quoteresource_id'));
                break;
            }
        }

        //filter recurring child quotes
        if (request('filter_recurring_option') == 'recurring_quotes') {
            $quotes->where('bill_recurring', 'yes');
        }
        if (request()->filled('filter_recurring_child') || request('filter_recurring_option') == 'child_quotes') {
            $quotes->where('bill_recurring_child', 'yes');
        }
        if (request()->filled('filter_recurring_parent_id')) {
            $quotes->where('bill_recurring_child', 'yes');
            $quotes->where('bill_recurring_parent_id', request('filter_recurring_parent_id'));
        }

        //stats: - due
        if (isset($data['stats']) && (in_array($data['stats'], [
            'sum-due-balances',
            'count-due',
        ]))) {
            $quotes->where('bill_status', 'due');
        }

        //stats: - overdue
        if (isset($data['stats']) && (in_array($data['stats'], [
            'sum-overdue-balances',
            'count-overdue',
        ]))) {
            $quotes->where('bill_status', 'overdue');
        }

        //stats: - always exclude draft quotes
        if (isset($data['stats']) && (in_array($data['stats'], [
            'count-all',
            'count-due',
            'count-overdue',
            'sum-all',
            'sum-payments',
            'sum-due-balances',
            'sum-overdue-balances',
        ]))) {
            $quotes->whereNotIn('bill_status', ['draft']);
        }

        //filter category
        if (is_array(request('filter_bill_categoryid')) && !empty(array_filter(request('filter_bill_categoryid')))) {
            $quotes->whereIn('bill_categoryid', request('filter_bill_categoryid'));
        }

        //filter created by
        if (is_array(request('filter_bill_creatorid')) && !empty(array_filter(request('filter_bill_creatorid')))) {
            $quotes->whereIn('bill_creatorid', request('filter_bill_creatorid'));
        }

        //filter status
        if (is_array(request('filter_bill_status')) && !empty(array_filter(request('filter_bill_status')))) {
            $quotes->whereIn('bill_status', request('filter_bill_status'));
        }

        //get specified list of quotes
        if (isset($data['list']) && is_array($data['list'])) {
            $quotes->whereIn('bill_quoteid', $data['list']);
        }

        //filter - exlude draft quotes
        if (request('filter_quote_exclude_status') == 'draft') {
            $quotes->whereNotIn('bill_status', ['draft']);
        }

        //search: various client columns and relationships (where first, then wherehas)
        if (request()->filled('search_query') || request()->filled('query')) {
            $quotes->where(function ($query) {
                //clean for quote id search
                $bill_quoteid = str_replace(config('system.settings_quotes_prefix'), '', request('search_query'));
                $bill_quoteid = preg_replace("/[^0-9.,]/", '', $bill_quoteid);
                $bill_quoteid = ltrim($bill_quoteid, '0');
                $query->Where('bill_quoteid', '=', $bill_quoteid);

                if (is_numeric(request('search_query'))) {
                    $query->orWhere('bill_final_amount', '=', request('search_query'));
                }
                $query->orWhere('bill_date', '=', date('Y-m-d', strtotime(request('search_query'))));
                $query->orWhere('bill_due_date', '=', date('Y-m-d', strtotime(request('search_query'))));
                $query->orWhere('bill_status', '=', request('search_query'));
                $query->orWhere('pickup_location', '=', request('search_query'));
                $query->orWhere('pickup_telefax', '=', request('search_query'));
                $query->orWhere('contact_person', '=', request('search_query'));
                $query->orWhere('contact_details', '=', request('search_query'));
                $query->orWhere('cargo_commodity', '=', request('search_query'));
                $query->orWhere('cargo_weight', '=', request('search_query'));
                $query->orWhere('pickup_phone', '=', request('search_query'));
                $query->orWhere('pickup_email', '=', request('search_query'));
                $query->orWhere('pickup_gstin', '=', request('search_query'));
                $query->orWhere('delivery_location', '=', request('search_query'));
                $query->orWhere('delivery_telefax', '=', request('search_query'));
                $query->orWhere('delivery_phone', '=', request('search_query'));
                $query->orWhere('delivery_email', '=', request('search_query'));
                $query->orWhere('delivery_gstin', '=', request('search_query'));
                $query->orWhereHas('tags', function ($q) {
                    $q->where('tag_title', 'LIKE', '%' . request('search_query') . '%');
                });
                $query->orWhereHas('category', function ($q) {
                    $q->where('category_name', 'LIKE', '%' . request('search_query') . '%');
                });
                $query->orWhereHas('client', function ($q) {
                    $q->where('client_company_name', 'LIKE', '%' . request('search_query') . '%');
                });
                $query->orWhereHas('project', function ($q) {
                    $q->where('project_title', 'LIKE', '%' . request('search_query') . '%');
                });
            });

        }

        //sorting
        if (in_array(request('sortorder'), array('desc', 'asc')) && request('orderby') != '') {
            //direct column name
            if (Schema::hasColumn('quotes', request('orderby'))) {
                $quotes->orderBy(request('orderby'), request('sortorder'));
            }
            //others
            switch (request('orderby')) {
            case 'client':
                $quotes->orderBy('client_company_name', request('sortorder'));
                break;
            case 'project':
                $quotes->orderBy('project_title', request('sortorder'));
                break;
            case 'payments':
                $quotes->orderBy('sum_payments', request('sortorder'));
                break;
            case 'balance':
                $quotes->orderBy('quote_balance', request('sortorder'));
                break;
            case 'category':
                $quotes->orderBy('category_name', request('sortorder'));
                break;
            }
        } else {
            //default sorting
            $quotes->orderBy(
                config('settings.ordering_quotes.sort_by'),
                config('settings.ordering_quotes.sort_order')
            );
        }

        //eager load
        $quotes->with([
            'payments',
            'tags',
            'taxes',
        ]);

        //stats - sum all
        if (isset($data['stats']) && $data['stats'] == 'sum-all') {
            return $quotes->get()->sum('bill_final_amount');
        }

        //stats - sum balances
        if (isset($data['stats']) && in_array($data['stats'], [
            'sum-payments',
        ])) {
            return $quotes->get()->sum('sum_payments');
        }

        //stats - sum balances
        if (isset($data['stats']) && in_array($data['stats'], [
            'sum-due-balances',
            'sum-overdue-balances',
        ])) {
            return $quotes->get()->sum('quote_balance');
        }

        //stats - count all
        if (isset($data['stats']) && in_array($data['stats'], [
            'count-all',
            'count-due',
            'count-overdue',
        ])) {
            return $quotes->count();
        }

        // Get the results and return them.
        if (isset($data['limit']) && is_numeric($data['limit'])) {
            $limit = $data['limit'];
        } else {
            $limit = config('system.settings_system_pagination_limits');
        }

        // Get the results and return them.
        return $quotes->paginate($limit);
    }

    /**
     * Create a new record
     * @param array $data payload data
     * @return mixed int|bool
     */
    public function create() {

        //save new user
        $quote = new $this->quotes;

        //data
        $quote->bill_clientid = request('bill_clientid');
        $quote->bill_projectid = request('bill_projectid');
        $quote->bill_creatorid = auth()->id();
        $quote->bill_categoryid = request('bill_categoryid');
        $quote->bill_date = request('bill_date');
        $quote->bill_due_date = request('bill_due_date');


        
        $quote->contact_mc_dot_number = request('contact_mc_dot_number');
        $quote->contact_name = request('contact_name');
        $quote->contact_phone = request('contact_phone');
        $quote->contact_term = request('contact_term');
        $quote->contact_fax = request('contact_fax');
        $quote->contact_address = request('contact_address');
        $quote->contact_driver = request('contact_driver');
        $quote->contact_dispatcher = request('contact_dispatcher');
        $quote->contact_truck = request('contact_truck');
        $quote->contact_trailer = request('contact_trailer');
        $quote->load_mode = request('load_mode');
        $quote->load_trailer_type = request('load_trailer_type');
        $quote->load_trailer_size = request('load_trailer_size');
        $quote->load_linear_feet = request('load_linear_feet');
        $quote->load_temperature = request('load_temperature');
        $quote->load_pallet_case_count = request('load_pallet_case_count');
        $quote->load_hazmat = request('load_hazmat');
        $quote->load_requirements = request('load_requirements');
        $quote->load_instructions = request('load_instructions');
        $quote->load_length = request('load_length');
        $quote->load_width = request('load_width');
        $quote->load_height = request('load_height');
        $quote->pickup_phone = request('pickup_phone');
        $quote->delivery_phone = request('delivery_phone');
        $quote->carrier_unloading = request('carrier_unloading');
        $quote->carrier_pallet_exchange = request('carrier_pallet_exchange');
        $quote->carrier_estimated_weight = request('carrier_estimated_weight');

        $quote->pickup_location = request('pickup_location');
        $quote->pickup_telefax = request('pickup_telefax');
        $quote->pickup_phone = request('pickup_phone');
        $quote->pickup_email = request('pickup_email');
        $quote->pickup_gstin = request('pickup_gstin');
        $quote->delivery_location = request('delivery_location');
        $quote->delivery_telefax = request('delivery_telefax');
        $quote->delivery_phone = request('delivery_phone');
        $quote->delivery_email = request('delivery_email');
        $quote->delivery_gstin = request('delivery_gstin');
        $quote->contact_person = request('contact_person');
        $quote->contact_details = request('contact_details');
        $quote->cargo_commodity = request('cargo_commodity');
        $quote->cargo_weight = request('cargo_weight');

        $quote->bill_terms = request('bill_terms');
        $quote->bill_notes = request('bill_notes');

        //save and return id
        if ($quote->save()) {
            return $quote->bill_quoteid;
        } else {
            Log::error("unable to create record - database error", ['process' => '[QuoteRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }
    }

    /**
     * update a record
     * @param int $id record id
     * @return mixed int|bool
     */
    public function update($id) {

        //get the record
        if (!$quote = $this->quotes->find($id)) {
            return false;
        }

        //general
        $quote->bill_categoryid = request('bill_categoryid');
        $quote->bill_date = request('bill_date');
        $quote->bill_due_date = request('bill_due_date');


        
        $quote->contact_mc_dot_number = request('contact_mc_dot_number');
        $quote->contact_name = request('contact_name');
        $quote->contact_phone = request('contact_phone');
        $quote->contact_term = request('contact_term');
        $quote->contact_fax = request('contact_fax');
        $quote->contact_address = request('contact_address');
        $quote->contact_dispatcher = request('contact_dispatcher');
        $quote->contact_driver = request('contact_driver');
        $quote->contact_truck = request('contact_truck');
        $quote->contact_trailer = request('contact_trailer');
        $quote->load_mode = request('load_mode');
        $quote->load_trailer_type = request('load_trailer_type');
        $quote->load_trailer_size = request('load_trailer_size');
        $quote->load_linear_feet = request('load_linear_feet');
        $quote->load_temperature = request('load_temperature');
        $quote->load_pallet_case_count = request('load_pallet_case_count');
        $quote->load_hazmat = request('load_hazmat');
        $quote->load_requirements = request('load_requirements');
        $quote->load_instructions = request('load_instructions');
        $quote->load_length = request('load_length');
        $quote->load_width = request('load_width');
        $quote->load_height = request('load_height');
        $quote->pickup_phone = request('pickup_phone');
        $quote->delivery_phone = request('delivery_phone');
        $quote->carrier_unloading = request('carrier_unloading');
        $quote->carrier_pallet_exchange = request('carrier_pallet_exchange');
        $quote->carrier_estimated_weight = request('carrier_estimated_weight');

        $quote->pickup_location = request('pickup_location');
        $quote->pickup_telefax = request('pickup_telefax');
        $quote->pickup_phone = request('pickup_phone');
        $quote->pickup_email = request('pickup_email');
        $quote->pickup_gstin = request('pickup_gstin');
        $quote->delivery_location = request('delivery_location');
        $quote->delivery_telefax = request('delivery_telefax');
        $quote->delivery_phone = request('delivery_phone');
        $quote->delivery_email = request('delivery_email');
        $quote->delivery_gstin = request('delivery_gstin');
        $quote->contact_person = request('contact_person');
        $quote->contact_details = request('contact_details');
        $quote->cargo_commodity = request('cargo_commodity');
        $quote->cargo_weight = request('cargo_weight');

        $quote->bill_notes = request('bill_notes');
        $quote->bill_terms = request('bill_terms');

        //save
        if ($quote->save()) {
            return $quote->bill_quoteid;
        } else {
            Log::error("unable to update record - database error", ['process' => '[EstimateRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }
    }

    /**
     * refresh an quote
     * @param mixed $quote can be an quote id or an quote object
     * @return null
     */
    public function refreshQuote($quote) {

        //get the quote
        if (is_numeric($quote)) {
            if ($quotes = $this->search($quote)) {
                $quote = $quotes->first();
            }
        }

        if (!$quote instanceof \App\Models\Quote) {
            return false;
        }

        //change dates to carbon format
        $bill_date = \Carbon\Carbon::parse($quote->bill_date);
        $bill_due_date = \Carbon\Carbon::parse($quote->bill_due_date);

        //quote status for none draft quotes
        if ($quote->bill_status != 'draft') {

            //quote status - due
            if ($quote->quote_balance > 0) {
                $quote->bill_status = 'due';
            }

            //quote status - paid
            if ($quote->bill_final_amount > 0 && $quote->quote_balance <= 0) {
                $quote->bill_status = 'paid';
            }

            //quote is overdue
            if ($quote->bill_status == 'due' || $quote->bill_status == 'part_paid') {
                if ($bill_due_date->diffInDays(today(), false) > 0) {
                    $quote->bill_status = 'overdue';
                }
            }

            //overdue quote with date updated
            if ($quote->bill_status == 'overdue') {
                if ($bill_due_date->diffInDays(today(), false) < 0) {
                    $quote->bill_status = 'due';
                }
            }

        }

        //update quote
        $quote->save();
    }

    /**
     * update an quote from he edit quote page
     * @param int $id record id
     * @return bool
     */
    public function updateQuote($id) {

        //get the record
        if (!$quote = $this->quotes->find($id)) {
            return false;
        }

        $quote->pickup_location = request('pickup_location');
        $quote->pickup_telefax = request('pickup_telefax');
        $quote->pickup_phone = request('pickup_phone');
        $quote->pickup_email = request('pickup_email');
        $quote->pickup_gstin = request('pickup_gstin');
        $quote->delivery_location = request('delivery_location');
        $quote->delivery_telefax = request('delivery_telefax');
        $quote->delivery_phone = request('delivery_phone');
        $quote->delivery_email = request('delivery_email');
        $quote->delivery_gstin = request('delivery_gstin');
        $quote->contact_person = request('contact_person');
        $quote->contact_details = request('contact_details');
        $quote->cargo_commodity = request('cargo_commodity');
        $quote->cargo_weight = request('cargo_weight');


        
        $quote->contact_mc_dot_number = request('contact_mc_dot_number');
        $quote->contact_name = request('contact_name');
        $quote->contact_phone = request('contact_phone');
        $quote->contact_term = request('contact_term');
        $quote->contact_fax = request('contact_fax');
        $quote->contact_address = request('contact_address');
        $quote->contact_dispatcher = request('contact_dispatcher');
        $quote->contact_driver = request('contact_driver');
        $quote->contact_truck = request('contact_truck');
        $quote->contact_trailer = request('contact_trailer');
        $quote->load_mode = request('load_mode');
        $quote->load_trailer_type = request('load_trailer_type');
        $quote->load_trailer_size = request('load_trailer_size');
        $quote->load_linear_feet = request('load_linear_feet');
        $quote->load_temperature = request('load_temperature');
        $quote->load_pallet_case_count = request('load_pallet_case_count');
        $quote->load_hazmat = request('load_hazmat');
        $quote->load_requirements = request('load_requirements');
        $quote->load_instructions = request('load_instructions');
        $quote->load_length = request('load_length');
        $quote->load_width = request('load_width');
        $quote->load_height = request('load_height');
        $quote->pickup_phone = request('pickup_phone');
        $quote->delivery_phone = request('delivery_phone');
        $quote->carrier_unloading = request('carrier_unloading');
        $quote->carrier_pallet_exchange = request('carrier_pallet_exchange');
        $quote->carrier_estimated_weight = request('carrier_estimated_weight');

        $quote->bill_date = request('bill_date');
        $quote->bill_due_date = request('bill_due_date');
        $quote->bill_terms = request('bill_terms');
        $quote->bill_notes = request('bill_notes');
        $quote->bill_subtotal = request('bill_subtotal');
        $quote->bill_amount_before_tax = request('bill_amount_before_tax');
        $quote->bill_final_amount = request('bill_final_amount');
        $quote->bill_tax_type = request('bill_tax_type');
        $quote->bill_tax_total_percentage = request('bill_tax_total_percentage');
        $quote->bill_tax_total_amount = request('bill_tax_total_amount');
        $quote->bill_discount_type = request('bill_discount_type');
        $quote->bill_discount_percentage = request('bill_discount_percentage');
        $quote->bill_discount_amount = request('bill_discount_amount');
        $quote->bill_adjustment_description = request('bill_adjustment_description');
        $quote->bill_adjustment_amount = request('bill_adjustment_amount');

        //save
        $quote->save();
    }

    /**
     * save each quoteline item
     * (1) get all existing line items and unlink them from expenses or timers
     * (2) delete all existing line items
     * (3) save each line item
     * @param int $bill_quoteid quote id
     * @return array
     */
    public function saveLineItems($bill_quoteid = '') {

        Log::info("saving quote line items - started", ['process' => '[QuoteRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'quote_id' => $bill_quoteid ?? '']);

        //validation
        if (!is_numeric($bill_quoteid)) {
            Log::error("validation error - required information is missing", ['process' => '[QuoteRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }

        //unlink linked items (expenses & timers)
        if (!$this->unlinkItems($bill_quoteid)) {
            return false;
        }

        //delete line items
        \App\Models\Lineitem::Where('lineitemresource_type', 'quote')
            ->where('lineitemresource_id', $bill_quoteid)
            ->delete();

        //default position
        $position = 0;

        //loopthrough each posted line item (use description to start the loop)
        if (is_array(request('js_item_description'))) {
            foreach (request('js_item_description') as $key => $description) {

                //next position (simple increment)
                $position++;

                //skip invalid items
                if (request('js_item_description')[$key] == '' || request('js_item_unit')[$key] == '') {
                    Log::error("invalid quote line item...skipping it", ['process' => '[QuoteRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
                    continue;
                }

                //skip invalid items
                if (!is_numeric(request('js_item_rate')[$key]) || !is_numeric(request('js_item_total')[$key])) {
                    Log::error("invalid quote line item...skipping it", ['process' => '[QuoteRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
                    continue;
                }

                //save lineitem to database
                if (request('js_item_type')[$key] == 'plain') {

                    //validate
                    if (!is_numeric(request('js_item_quantity')[$key])) {
                        Log::error("invalid quote line item (plain) ...skipping it", ['process' => '[QuoteRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
                        continue;
                    }

                    $line = [
                        'lineitem_description' => request('js_item_description')[$key],
                        'lineitem_quantity' => request('js_item_quantity')[$key],
                        'lineitem_rate' => request('js_item_rate')[$key],
                        'lineitem_unit' => request('js_item_unit')[$key],
                        'lineitem_total' => request('js_item_total')[$key],
                        'lineitemresource_linked_type' => request('js_item_linked_type')[$key],
                        'lineitemresource_linked_id' => request('js_item_linked_id')[$key],
                        'lineitem_type' => request('js_item_type')[$key],
                        'lineitem_position' => $position,
                        'lineitemresource_type' => 'quote',
                        'lineitemresource_id' => $bill_quoteid,
                        'lineitem_time_timers_list' => null,
                        'lineitem_time_hours' => null,
                        'lineitem_time_minutes' => null,
                    ];
                    $this->lineitemrepo->create($line);
                }

                //save time item to database
                if (request('js_item_type')[$key] == 'time') {

                    //validate
                    if (!is_numeric(request('js_item_hours')[$key]) || !is_numeric(request('js_item_minutes')[$key])) {
                        Log::error("invalid quote line item (time) ...skipping it", ['process' => '[QuoteRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
                        continue;
                    }

                    $line = [
                        'lineitem_description' => request('js_item_description')[$key],
                        'lineitem_quantity' => null,
                        'lineitem_rate' => request('js_item_rate')[$key],
                        'lineitem_unit' => request('js_item_unit')[$key],
                        'lineitem_total' => request('js_item_total')[$key],
                        'lineitemresource_linked_type' => request('js_item_linked_type')[$key],
                        'lineitemresource_linked_id' => request('js_item_linked_id')[$key],
                        'lineitem_type' => request('js_item_type')[$key],
                        'lineitem_position' => $position,
                        'lineitemresource_type' => 'quote',
                        'lineitemresource_id' => $bill_quoteid,
                        'lineitem_time_hours' => request('js_item_hours')[$key],
                        'lineitem_time_minutes' => request('js_item_minutes')[$key],
                        'lineitem_time_timers_list' => request('js_item_timers_list')[$key],

                    ];
                    $this->lineitemrepo->create($line);
                }

                //[link][expenses]
                if (request('js_item_linked_type')[$key] == 'expense' && request('js_item_linked_id')[$key]) {
                    \App\Models\Expense::where('expense_id', request('js_item_linked_id')[$key])
                        ->update([
                            'expense_billing_status' => 'quoted',
                            'expense_billable_quoteid' => $bill_quoteid,
                        ]);
                }

                //[link][task timers]
                if (request('js_item_linked_type')[$key] == 'timer' && is_numeric(request('js_item_linked_id')[$key])) {
                    $timers = explode(',', request('js_item_timers_list')[$key]);
                    \App\Models\Timer::whereIn('timer_id', $timers)
                        ->update([
                            'timer_billing_status' => 'quoted',
                            'timer_billing_quoteid' => $bill_quoteid,
                        ]);
                }
            }
        }
    }

    /**
     * unlink expenses or tmers linked to a particular quote
     * @param int $bill_quoteid quote id
     * @return bool
     */
    public function unlinkItems($bill_quoteid = '') {

        if (!is_numeric($bill_quoteid)) {
            Log::error("validation error - required information is missing", ['process' => '[QuoteRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }

        //[unlink][billed expense]
        \App\Models\Expense::where('expense_billable_quoteid', $bill_quoteid)
            ->update([
                'expense_billing_status' => 'not_quoted',
                'expense_billable_quoteid' => null,
            ]);

        //[unlink][billed task]
        \App\Models\Timer::where('timer_billing_quoteid', $bill_quoteid)
            ->update([
                'timer_billing_status' => 'not_quoted',
                'timer_billing_quoteid' => null,
            ]);

        return true;
    }

}