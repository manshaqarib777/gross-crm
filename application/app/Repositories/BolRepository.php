<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for bols
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Repositories;

use App\Models\Bol;
use App\Repositories\LineitemRepository;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Log;

class BolRepository {

    /**
     * The bols repository instance.
     */
    protected $bols;

    /**
     * Inject dependecies
     */
    public function __construct(Bol $bols, LineitemRepository $lineitemrepo) {
        $this->bols = $bols;
        $this->lineitemrepo = $lineitemrepo;
    }

    /**
     * Search model
     * @param int $id optional for getting a single, specified record
     * @param array $data optional data payload
     * @return object bol collection
     */
    public function search($id = '', $data = array()) {

        $bols = $this->bols->newQuery();

        //joins
        $bols->leftJoin('clients', 'clients.client_id', '=', 'bols.bill_clientid');
        $bols->leftJoin('projects', 'projects.project_id', '=', 'bols.bill_projectid');
        $bols->leftJoin('categories', 'categories.category_id', '=', 'bols.bill_categoryid');
        $bols->leftJoin('users', 'users.id', '=', 'bols.bill_creatorid');

        //join: users reminders - do not do this for cronjobs
        if (auth()->check()) {
            $bols->leftJoin('reminders', function ($join) {
                $join->on('reminders.reminderresource_id', '=', 'bols.bill_bolid')
                    ->where('reminders.reminderresource_type', '=', 'bol')
                    ->where('reminders.reminder_userid', '=', auth()->id());
            });
        }

        // all fields
        $bols->selectRaw('*');

        //count payments
        $bols->selectRaw('(SELECT COUNT(*)
                                      FROM payments
                                      WHERE payment_bolid = bols.bill_bolid)
                                      AS count_payments');

        //sum payments
        $bols->selectRaw('(SELECT COALESCE(SUM(payment_amount), 0)
                                      FROM payments WHERE payment_bolid = bols.bill_bolid
                                      GROUP BY payment_bolid)
                                      AS x_sum_payments');
        $bols->selectRaw('(SELECT COALESCE(x_sum_payments, 0.00))
                                      AS sum_payments');

        //bol balance
        $bols->selectRaw('(SELECT COALESCE(bill_final_amount - sum_payments, 0.00))
                                      AS bol_balance');

        //default where
        $bols->whereRaw("1 = 1");

        //filters: id
        if (request()->filled('filter_bill_bolid')) {
            $bols->where('bill_bolid', request('filter_bill_bolid'));
        }
        if (is_numeric($id)) {
            $bols->where('bill_bolid', $id);
        }

        //filter by subscription id
        if (isset($data['bill_subscriptionid'])) {
            $bols->where('bill_subscriptionid', $data['bill_subscriptionid']);
        }

        //do not show items that not yet ready (i.e exclude items in the process of being cloned that have status 'invisible')
        $bols->where('bill_visibility', 'visible');

        //filter clients
        if (request()->filled('filter_bill_clientid')) {
            $bols->where('bill_clientid', request('filter_bill_clientid'));
        }

        //filter projects
        if (request()->filled('filter_bill_projectid')) {
            $bols->where('bill_projectid', request('filter_bill_projectid'));
        }

        //filter: amount (min)
        if (request()->filled('filter_bill_final_amount_min')) {
            $bols->where('bill_final_amount', '>=', request('filter_bill_final_amount_min'));
        }

        //filter: amount (max)
        if (request()->filled('filter_bill_final_amount_max')) {
            $bols->where('bill_final_amount', '<=', request('filter_bill_final_amount_max'));
        }

        //filter: payments (max)
        if (request()->filled('filter_bol_payments_max')) {
            $bols->where('sum_payments', '>=', request('filter_bol_payments_max'));
        }

        //filter: bol (start)
        if (request()->filled('filter_bill_date_start')) {
            $bols->where('bill_date', '>=', request('filter_bill_date_start'));
        }

        //filter: bol (end)
        if (request()->filled('filter_bill_date_end')) {
            $bols->where('bill_date', '<=', request('filter_bill_date_end'));
        }

        //filter: bol (start)
        if (request()->filled('filter_bill_due_date_start')) {
            $bols->whereDate('bill_due_date', '>=', request('filter_bill_due_date_start'));
        }

        //filter: bol (end)
        if (request()->filled('filter_bill_due_date_end')) {
            $bols->whereDate('bill_due_date', '<=', request('filter_bill_due_date_end'));
        }


        //filter by pickup location
        if (isset($data['pickup_location'])) {
            $bols->where('pickup_location', $data['pickup_location']);
        }


        //filter by contact_mc_dot_number
        if (isset($data['contact_mc_dot_number'])) {
            $bols->where('contact_mc_dot_number', $data['contact_mc_dot_number']);
        }

        //filter by contact_name
        if (isset($data['contact_name'])) {
            $bols->where('contact_name', $data['contact_name']);
        }

        //filter by contact_phone
        if (isset($data['contact_phone'])) {
            $bols->where('contact_phone', $data['contact_phone']);
        }

        //filter by contact_term
        if (isset($data['contact_term'])) {
            $bols->where('contact_term', $data['contact_term']);
        }

        //filter by contact_fax
        if (isset($data['contact_fax'])) {
            $bols->where('contact_fax', $data['contact_fax']);
        }

        //filter by contact_address
        if (isset($data['contact_address'])) {
            $bols->where('contact_address', $data['contact_address']);
        }
        //filter by contact_dispatcher
        if (isset($data['contact_dispatcher'])) {
            $bols->where('contact_dispatcher', $data['contact_dispatcher']);
        }

        //filter by contact_driver
        if (isset($data['contact_driver'])) {
            $bols->where('contact_driver', $data['contact_driver']);
        }

        //filter by contact_truck
        if (isset($data['contact_truck'])) {
            $bols->where('contact_truck', $data['contact_truck']);
        }

        //filter by contact_trailer
        if (isset($data['contact_trailer'])) {
            $bols->where('contact_trailer', $data['contact_trailer']);
        }

        //filter by load_mode
        if (isset($data['load_mode'])) {
            $bols->where('load_mode', $data['load_mode']);
        }

        //filter by load_trailer_type
        if (isset($data['load_trailer_type'])) {
            $bols->where('load_trailer_type', $data['load_trailer_type']);
        }

        //filter by load_trailer_size
        if (isset($data['load_trailer_size'])) {
            $bols->where('load_trailer_size', $data['load_trailer_size']);
        }

        //filter by load_linear_feet
        if (isset($data['load_linear_feet'])) {
            $bols->where('load_linear_feet', $data['load_linear_feet']);
        }

        //filter by load_temperature
        if (isset($data['load_temperature'])) {
            $bols->where('load_temperature', $data['load_temperature']);
        }

        //filter by load_pallet_case_count
        if (isset($data['load_pallet_case_count'])) {
            $bols->where('load_pallet_case_count', $data['load_pallet_case_count']);
        }

        //filter by load_hazmat
        if (isset($data['load_hazmat'])) {
            $bols->where('load_hazmat', $data['load_hazmat']);
        }

        //filter by load_requirements
        if (isset($data['load_requirements'])) {
            $bols->where('load_requirements', $data['load_requirements']);
        }

        //filter by load_instructions
        if (isset($data['load_instructions'])) {
            $bols->where('load_instructions', $data['load_instructions']);
        }

        //filter by load_length
        if (isset($data['load_length'])) {
            $bols->where('load_length', $data['load_length']);
        }

        //filter by load_width
        if (isset($data['load_width'])) {
            $bols->where('load_width', $data['load_width']);
        }

        //filter by load_height
        if (isset($data['load_height'])) {
            $bols->where('load_height', $data['load_height']);
        }


        //filter by pickup_time
        if (isset($data['pickup_time'])) {
            $bols->where('pickup_time', $data['pickup_time']);
        }

        //filter by delivery_time
        if (isset($data['delivery_time'])) {
            $bols->where('delivery_time', $data['delivery_time']);
        }

        //filter by carrier_unloading
        if (isset($data['carrier_unloading'])) {
            $bols->where('carrier_unloading', $data['carrier_unloading']);
        }

        //filter by carrier_pallet_exchange
        if (isset($data['carrier_pallet_exchange'])) {
            $bols->where('carrier_pallet_exchange', $data['carrier_pallet_exchange']);
        }

        //filter by carrier_estimated_weight
        if (isset($data['carrier_estimated_weight'])) {
            $bols->where('carrier_estimated_weight', $data['carrier_estimated_weight']);
        }


        //filter by pickup telefax
        if (isset($data['pickup_date'])) {
            $bols->where('pickup_date', $data['pickup_date']);
        }            
        
        //filter by pickup phone
        if (isset($data['pickup_time'])) {
            $bols->where('pickup_time', $data['pickup_time']);
        }            
        
        //filter by pickup email
        if (isset($data['pickup_email'])) {
            $bols->where('pickup_email', $data['pickup_email']);
        }            
        
        //filter by pickup gstin
        if (isset($data['pickup_gstin'])) {
            $bols->where('pickup_gstin', $data['pickup_gstin']);
        }


        //filter by delivery location
        if (isset($data['delivery_location'])) {
            $bols->where('delivery_location', $data['delivery_location']);
        }

        //filter by delivery telefax
        if (isset($data['delivery_date'])) {
            $bols->where('delivery_date', $data['delivery_date']);
        }            
        
        //filter by delivery phone
        if (isset($data['delivery_time'])) {
            $bols->where('delivery_time', $data['delivery_time']);
        }            
        
        //filter by delivery email
        if (isset($data['delivery_email'])) {
            $bols->where('delivery_email', $data['delivery_email']);
        }            
        
        //filter by delivery gstin
        if (isset($data['delivery_gstin'])) {
            $bols->where('delivery_gstin', $data['delivery_location']);
        }

        //filter by contact person
        if (isset($data['contact_person'])) {
            $bols->where('contact_person', $data['contact_person']);
        }

        //filter by contact details
        if (isset($data['contact_details'])) {
            $bols->where('contact_details', $data['contact_details']);
        }

        //filter by cargo commodity
        if (isset($data['cargo_commodity'])) {
            $bols->where('cargo_commodity', $data['cargo_commodity']);
        }

        //filter by cargo weight
        if (isset($data['cargo_weight'])) {
            $bols->where('cargo_weight', $data['cargo_weight']);
        }



        //resource filtering
        if (request()->filled('bolresource_type') && request()->filled('bolresource_id')) {
            switch (request('bolresource_type')) {
            case 'client':
                $bols->where('bill_clientid', request('bolresource_id'));
                break;
            case 'project':
                $bols->where('bill_projectid', request('bolresource_id'));
                break;
            }
        }

        //filter recurring child bols
        if (request('filter_recurring_option') == 'recurring_bols') {
            $bols->where('bill_recurring', 'yes');
        }
        if (request()->filled('filter_recurring_child') || request('filter_recurring_option') == 'child_bols') {
            $bols->where('bill_recurring_child', 'yes');
        }
        if (request()->filled('filter_recurring_parent_id')) {
            $bols->where('bill_recurring_child', 'yes');
            $bols->where('bill_recurring_parent_id', request('filter_recurring_parent_id'));
        }

        //stats: - due
        if (isset($data['stats']) && (in_array($data['stats'], [
            'sum-due-balances',
            'count-due',
        ]))) {
            $bols->where('bill_status', 'due');
        }

        //stats: - overdue
        if (isset($data['stats']) && (in_array($data['stats'], [
            'sum-overdue-balances',
            'count-overdue',
        ]))) {
            $bols->where('bill_status', 'overdue');
        }

        //stats: - always exclude draft bols
        if (isset($data['stats']) && (in_array($data['stats'], [
            'count-all',
            'count-due',
            'count-overdue',
            'sum-all',
            'sum-payments',
            'sum-due-balances',
            'sum-overdue-balances',
        ]))) {
            $bols->whereNotIn('bill_status', ['draft']);
        }

        //filter category
        if (is_array(request('filter_bill_categoryid')) && !empty(array_filter(request('filter_bill_categoryid')))) {
            $bols->whereIn('bill_categoryid', request('filter_bill_categoryid'));
        }

        //filter created by
        if (is_array(request('filter_bill_creatorid')) && !empty(array_filter(request('filter_bill_creatorid')))) {
            $bols->whereIn('bill_creatorid', request('filter_bill_creatorid'));
        }

        //filter status
        if (is_array(request('filter_bill_status')) && !empty(array_filter(request('filter_bill_status')))) {
            $bols->whereIn('bill_status', request('filter_bill_status'));
        }

        //get specified list of bols
        if (isset($data['list']) && is_array($data['list'])) {
            $bols->whereIn('bill_bolid', $data['list']);
        }

        //filter - exlude draft bols
        if (request('filter_bol_exclude_status') == 'draft') {
            $bols->whereNotIn('bill_status', ['draft']);
        }

        //search: various client columns and relationships (where first, then wherehas)
        if (request()->filled('search_query') || request()->filled('query')) {
            $bols->where(function ($query) {
                //clean for bol id search
                $bill_bolid = str_replace(config('system.settings_bols_prefix'), '', request('search_query'));
                $bill_bolid = preg_replace("/[^0-9.,]/", '', $bill_bolid);
                $bill_bolid = ltrim($bill_bolid, '0');
                $query->Where('bill_bolid', '=', $bill_bolid);

                if (is_numeric(request('search_query'))) {
                    $query->orWhere('bill_final_amount', '=', request('search_query'));
                }
                $query->orWhere('bill_date', '=', date('Y-m-d', strtotime(request('search_query'))));
                $query->orWhere('bill_due_date', '=', date('Y-m-d', strtotime(request('search_query'))));
                $query->orWhere('bill_status', '=', request('search_query'));
                $query->orWhere('pickup_location', '=', request('search_query'));
                $query->orWhere('pickup_date', '=', request('search_query'));
                $query->orWhere('contact_person', '=', request('search_query'));
                $query->orWhere('contact_details', '=', request('search_query'));
                $query->orWhere('cargo_commodity', '=', request('search_query'));
                $query->orWhere('cargo_weight', '=', request('search_query'));
                $query->orWhere('pickup_time', '=', request('search_query'));
                $query->orWhere('pickup_email', '=', request('search_query'));
                $query->orWhere('pickup_gstin', '=', request('search_query'));
                $query->orWhere('delivery_location', '=', request('search_query'));
                $query->orWhere('delivery_date', '=', request('search_query'));
                $query->orWhere('delivery_time', '=', request('search_query'));
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
            if (Schema::hasColumn('bols', request('orderby'))) {
                $bols->orderBy(request('orderby'), request('sortorder'));
            }
            //others
            switch (request('orderby')) {
            case 'client':
                $bols->orderBy('client_company_name', request('sortorder'));
                break;
            case 'project':
                $bols->orderBy('project_title', request('sortorder'));
                break;
            case 'payments':
                $bols->orderBy('sum_payments', request('sortorder'));
                break;
            case 'balance':
                $bols->orderBy('bol_balance', request('sortorder'));
                break;
            case 'category':
                $bols->orderBy('category_name', request('sortorder'));
                break;
            }
        } else {
            //default sorting
            $bols->orderBy(
                config('settings.ordering_bols.sort_by'),
                config('settings.ordering_bols.sort_order')
            );
        }

        //eager load
        $bols->with([
            'payments',
            'tags',
            'taxes',
        ]);

        //stats - sum all
        if (isset($data['stats']) && $data['stats'] == 'sum-all') {
            return $bols->get()->sum('bill_final_amount');
        }

        //stats - sum balances
        if (isset($data['stats']) && in_array($data['stats'], [
            'sum-payments',
        ])) {
            return $bols->get()->sum('sum_payments');
        }

        //stats - sum balances
        if (isset($data['stats']) && in_array($data['stats'], [
            'sum-due-balances',
            'sum-overdue-balances',
        ])) {
            return $bols->get()->sum('bol_balance');
        }

        //stats - count all
        if (isset($data['stats']) && in_array($data['stats'], [
            'count-all',
            'count-due',
            'count-overdue',
        ])) {
            return $bols->count();
        }

        // Get the results and return them.
        if (isset($data['limit']) && is_numeric($data['limit'])) {
            $limit = $data['limit'];
        } else {
            $limit = config('system.settings_system_pagination_limits');
        }

        // Get the results and return them.
        return $bols->paginate($limit);
    }

    /**
     * Create a new record
     * @param array $data payload data
     * @return mixed int|bool
     */
    public function create() {

        //save new user
        $bol = new $this->bols;

        //data
        $bol->bill_clientid = request('bill_clientid');
        $bol->bill_projectid = request('bill_projectid');
        $bol->bill_creatorid = auth()->id();
        $bol->bill_categoryid = request('bill_categoryid');
        $bol->bill_date = request('bill_date');
        $bol->bill_due_date = request('bill_due_date');


        $bol->contact_mc_dot_number = request('contact_mc_dot_number');
        $bol->contact_name = request('contact_name');
        $bol->contact_phone = request('contact_phone');
        $bol->contact_term = request('contact_term');
        $bol->contact_fax = request('contact_fax');
        $bol->contact_address = request('contact_address');
        $bol->contact_driver = request('contact_driver');
        $bol->contact_dispatcher = request('contact_dispatcher');
        $bol->contact_truck = request('contact_truck');
        $bol->contact_trailer = request('contact_trailer');
        $bol->load_mode = request('load_mode');
        $bol->load_trailer_type = request('load_trailer_type');
        $bol->load_trailer_size = request('load_trailer_size');
        $bol->load_linear_feet = request('load_linear_feet');
        $bol->load_temperature = request('load_temperature');
        $bol->load_pallet_case_count = request('load_pallet_case_count');
        $bol->load_hazmat = request('load_hazmat');
        $bol->load_requirements = request('load_requirements');
        $bol->load_instructions = request('load_instructions');
        $bol->load_length = request('load_length');
        $bol->load_width = request('load_width');
        $bol->load_height = request('load_height');
        $bol->pickup_time = request('pickup_time');
        $bol->delivery_time = request('delivery_time');
        $bol->carrier_unloading = request('carrier_unloading');
        $bol->carrier_pallet_exchange = request('carrier_pallet_exchange');
        $bol->carrier_estimated_weight = request('carrier_estimated_weight');


        $bol->pickup_location = request('pickup_location');
        $bol->pickup_date = request('pickup_date');
        $bol->pickup_time = request('pickup_time');
        $bol->pickup_email = request('pickup_email');
        $bol->pickup_gstin = request('pickup_gstin');
        $bol->delivery_location = request('delivery_location');
        $bol->delivery_date = request('delivery_date');
        $bol->delivery_time = request('delivery_time');
        $bol->delivery_email = request('delivery_email');
        $bol->delivery_gstin = request('delivery_gstin');
        $bol->contact_person = request('contact_person');
        $bol->contact_details = request('contact_details');
        $bol->cargo_commodity = request('cargo_commodity');
        $bol->cargo_weight = request('cargo_weight');

        $bol->bill_terms = request('bill_terms');
        $bol->bill_notes = request('bill_notes');

        //save and return id
        if ($bol->save()) {
            return $bol->bill_bolid;
        } else {
            Log::error("unable to create record - database error", ['process' => '[BolRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
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
        if (!$bol = $this->bols->find($id)) {
            return false;
        }

        //general
        $bol->bill_categoryid = request('bill_categoryid');
        $bol->bill_date = request('bill_date');
        $bol->bill_due_date = request('bill_due_date');


        $bol->contact_mc_dot_number = request('contact_mc_dot_number');
        $bol->contact_name = request('contact_name');
        $bol->contact_phone = request('contact_phone');
        $bol->contact_term = request('contact_term');
        $bol->contact_fax = request('contact_fax');
        $bol->contact_address = request('contact_address');
        $bol->contact_driver = request('contact_driver');
        $bol->contact_dispatcher = request('contact_dispatcher');
        
        $bol->contact_truck = request('contact_truck');
        $bol->contact_trailer = request('contact_trailer');
        $bol->load_mode = request('load_mode');
        $bol->load_trailer_type = request('load_trailer_type');
        $bol->load_trailer_size = request('load_trailer_size');
        $bol->load_linear_feet = request('load_linear_feet');
        $bol->load_temperature = request('load_temperature');
        $bol->load_pallet_case_count = request('load_pallet_case_count');
        $bol->load_hazmat = request('load_hazmat');
        $bol->load_requirements = request('load_requirements');
        $bol->load_instructions = request('load_instructions');
        $bol->load_length = request('load_length');
        $bol->load_width = request('load_width');
        $bol->load_height = request('load_height');
        $bol->pickup_time = request('pickup_time');
        $bol->delivery_time = request('delivery_time');
        $bol->carrier_unloading = request('carrier_unloading');
        $bol->carrier_pallet_exchange = request('carrier_pallet_exchange');
        $bol->carrier_estimated_weight = request('carrier_estimated_weight');

        $bol->pickup_location = request('pickup_location');
        $bol->pickup_date = request('pickup_date');
        $bol->pickup_time = request('pickup_time');
        $bol->pickup_email = request('pickup_email');
        $bol->pickup_gstin = request('pickup_gstin');
        $bol->delivery_location = request('delivery_location');
        $bol->delivery_date = request('delivery_date');
        $bol->delivery_time = request('delivery_time');
        $bol->delivery_email = request('delivery_email');
        $bol->delivery_gstin = request('delivery_gstin');
        $bol->contact_person = request('contact_person');
        $bol->contact_details = request('contact_details');
        $bol->cargo_commodity = request('cargo_commodity');
        $bol->cargo_weight = request('cargo_weight');

        $bol->bill_notes = request('bill_notes');
        $bol->bill_terms = request('bill_terms');

        //save
        if ($bol->save()) {
            return $bol->bill_bolid;
        } else {
            Log::error("unable to update record - database error", ['process' => '[EstimateRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }
    }

    /**
     * refresh an bol
     * @param mixed $bol can be an bol id or an bol object
     * @return null
     */
    public function refreshBol($bol) {

        //get the bol
        if (is_numeric($bol)) {
            if ($bols = $this->search($bol)) {
                $bol = $bols->first();
            }
        }

        if (!$bol instanceof \App\Models\Bol) {
            return false;
        }

        //change dates to carbon format
        $bill_date = \Carbon\Carbon::parse($bol->bill_date);
        $bill_due_date = \Carbon\Carbon::parse($bol->bill_due_date);

        //bol status for none draft bols
        if ($bol->bill_status != 'draft') {

            //bol status - due
            if ($bol->bol_balance > 0) {
                $bol->bill_status = 'due';
            }

            //bol status - paid
            if ($bol->bill_final_amount > 0 && $bol->bol_balance <= 0) {
                $bol->bill_status = 'paid';
            }

            //bol is overdue
            if ($bol->bill_status == 'due' || $bol->bill_status == 'part_paid') {
                if ($bill_due_date->diffInDays(today(), false) > 0) {
                    $bol->bill_status = 'overdue';
                }
            }

            //overdue bol with date updated
            if ($bol->bill_status == 'overdue') {
                if ($bill_due_date->diffInDays(today(), false) < 0) {
                    $bol->bill_status = 'due';
                }
            }

        }

        //update bol
        $bol->save();
    }

    /**
     * update an bol from he edit bol page
     * @param int $id record id
     * @return bool
     */
    public function updateBol($id) {

        //get the record
        if (!$bol = $this->bols->find($id)) {
            return false;
        }

        $bol->pickup_location = request('pickup_location');
        $bol->pickup_date = request('pickup_date');
        $bol->pickup_time = request('pickup_time');
        $bol->pickup_email = request('pickup_email');
        $bol->pickup_gstin = request('pickup_gstin');
        $bol->delivery_location = request('delivery_location');
        $bol->delivery_date = request('delivery_date');
        $bol->delivery_time = request('delivery_time');
        $bol->delivery_email = request('delivery_email');
        $bol->delivery_gstin = request('delivery_gstin');
        $bol->contact_person = request('contact_person');
        $bol->contact_details = request('contact_details');
        $bol->cargo_commodity = request('cargo_commodity');
        $bol->cargo_weight = request('cargo_weight');



        $bol->contact_mc_dot_number = request('contact_mc_dot_number');
        $bol->contact_name = request('contact_name');
        $bol->contact_phone = request('contact_phone');
        $bol->contact_term = request('contact_term');
        $bol->contact_fax = request('contact_fax');
        $bol->contact_address = request('contact_address');
        $bol->contact_driver = request('contact_driver');
        $bol->contact_dispatcher = request('contact_dispatcher');
        $bol->contact_truck = request('contact_truck');
        $bol->contact_trailer = request('contact_trailer');
        $bol->load_mode = request('load_mode');
        $bol->load_trailer_type = request('load_trailer_type');
        $bol->load_trailer_size = request('load_trailer_size');
        $bol->load_linear_feet = request('load_linear_feet');
        $bol->load_temperature = request('load_temperature');
        $bol->load_pallet_case_count = request('load_pallet_case_count');
        $bol->load_hazmat = request('load_hazmat');
        $bol->load_requirements = request('load_requirements');
        $bol->load_instructions = request('load_instructions');
        $bol->load_length = request('load_length');
        $bol->load_width = request('load_width');
        $bol->load_height = request('load_height');
        $bol->pickup_time = request('pickup_time');
        $bol->delivery_time = request('delivery_time');
        $bol->carrier_unloading = request('carrier_unloading');
        $bol->carrier_pallet_exchange = request('carrier_pallet_exchange');
        $bol->carrier_estimated_weight = request('carrier_estimated_weight');

        $bol->bill_date = request('bill_date');
        $bol->bill_due_date = request('bill_due_date');
        $bol->bill_terms = request('bill_terms');
        $bol->bill_notes = request('bill_notes');
        $bol->bill_subtotal = request('bill_subtotal');
        $bol->bill_amount_before_tax = request('bill_amount_before_tax');
        $bol->bill_final_amount = request('bill_final_amount');
        $bol->bill_tax_type = request('bill_tax_type');
        $bol->bill_tax_total_percentage = request('bill_tax_total_percentage');
        $bol->bill_tax_total_amount = request('bill_tax_total_amount');
        $bol->bill_discount_type = request('bill_discount_type');
        $bol->bill_discount_percentage = request('bill_discount_percentage');
        $bol->bill_discount_amount = request('bill_discount_amount');
        $bol->bill_adjustment_description = request('bill_adjustment_description');
        $bol->bill_adjustment_amount = request('bill_adjustment_amount');

        //save
        $bol->save();
    }

    /**
     * save each bolline item
     * (1) get all existing line items and unlink them from expenses or timers
     * (2) delete all existing line items
     * (3) save each line item
     * @param int $bill_bolid bol id
     * @return array
     */
    public function saveLineItems($bill_bolid = '') {

        Log::info("saving bol line items - started", ['process' => '[BolRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'bol_id' => $bill_bolid ?? '']);

        //validation
        if (!is_numeric($bill_bolid)) {
            Log::error("validation error - required information is missing", ['process' => '[BolRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }

        //unlink linked items (expenses & timers)
        if (!$this->unlinkItems($bill_bolid)) {
            return false;
        }

        //delete line items
        \App\Models\Lineitem::Where('lineitemresource_type', 'bol')
            ->where('lineitemresource_id', $bill_bolid)
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
                    Log::error("invalid bol line item...skipping it", ['process' => '[BolRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
                    continue;
                }

                //skip invalid items
                if (!is_numeric(request('js_item_rate')[$key]) || !is_numeric(request('js_item_total')[$key])) {
                    Log::error("invalid bol line item...skipping it", ['process' => '[BolRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
                    continue;
                }

                //save lineitem to database
                if (request('js_item_type')[$key] == 'plain') {

                    //validate
                    if (!is_numeric(request('js_item_quantity')[$key])) {
                        Log::error("invalid bol line item (plain) ...skipping it", ['process' => '[BolRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
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
                        'lineitemresource_type' => 'bol',
                        'lineitemresource_id' => $bill_bolid,
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
                        Log::error("invalid bol line item (time) ...skipping it", ['process' => '[BolRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
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
                        'lineitemresource_type' => 'bol',
                        'lineitemresource_id' => $bill_bolid,
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
                            'expense_billing_status' => 'bold',
                            'expense_billable_bolid' => $bill_bolid,
                        ]);
                }

                //[link][task timers]
                if (request('js_item_linked_type')[$key] == 'timer' && is_numeric(request('js_item_linked_id')[$key])) {
                    $timers = explode(',', request('js_item_timers_list')[$key]);
                    \App\Models\Timer::whereIn('timer_id', $timers)
                        ->update([
                            'timer_billing_status' => 'bold',
                            'timer_billing_bolid' => $bill_bolid,
                        ]);
                }
            }
        }
    }

    /**
     * unlink expenses or tmers linked to a particular bol
     * @param int $bill_bolid bol id
     * @return bool
     */
    public function unlinkItems($bill_bolid = '') {

        if (!is_numeric($bill_bolid)) {
            Log::error("validation error - required information is missing", ['process' => '[BolRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }

        //[unlink][billed expense]
        \App\Models\Expense::where('expense_billable_bolid', $bill_bolid)
            ->update([
                'expense_billing_status' => 'not_bold',
                'expense_billable_bolid' => null,
            ]);

        //[unlink][billed task]
        \App\Models\Timer::where('timer_billing_bolid', $bill_bolid)
            ->update([
                'timer_billing_status' => 'not_bold',
                'timer_billing_bolid' => null,
            ]);

        return true;
    }

}