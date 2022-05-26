<?php

/** --------------------------------------------------------------------------------
 * This controller manages all the business logic for quotes
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Quotes\QuoteClone;
use App\Http\Requests\Quotes\QuoteRecurrringSettings;
use App\Http\Requests\Quotes\QuoteSave;
use App\Http\Requests\Quotes\QuoteStoreUpdate;
use App\Http\Responses\Common\ChangeCategoryResponse;
use App\Http\Responses\Quotes\AttachProjectResponse;
use App\Http\Responses\Quotes\ChangeCategoryUpdateResponse;
use App\Http\Responses\Quotes\CreateCloneResponse;
use App\Http\Responses\Quotes\CreateResponse;
use App\Http\Responses\Quotes\DestroyResponse;
use App\Http\Responses\Quotes\EditResponse;
use App\Http\Responses\Quotes\IndexResponse;
use App\Http\Responses\Quotes\PDFResponse;
use App\Http\Responses\Quotes\PublishResponse;
use App\Http\Responses\Quotes\RecurringSettingsResponse;
use App\Http\Responses\Quotes\ResendResponse;
use App\Http\Responses\Quotes\SaveResponse;
use App\Http\Responses\Quotes\ShowResponse;
use App\Http\Responses\Quotes\StoreCloneResponse;
use App\Http\Responses\Quotes\StoreResponse;
use App\Http\Responses\Quotes\UpdateResponse;
use App\Http\Responses\Pay\MolliePaymentResponse;
use App\Http\Responses\Pay\PaypalPaymentResponse;
use App\Http\Responses\Pay\RazorpayPaymentResponse;
use App\Http\Responses\Pay\StripePaymentResponse;
use App\Repositories\CategoryRepository;
use App\Repositories\ClientRepository;
use App\Repositories\CloneQuoteRepository;
use App\Repositories\DestroyRepository;
use App\Repositories\EmailerRepository;
use App\Repositories\EventRepository;
use App\Repositories\EventTrackingRepository;
use App\Repositories\QuoteGeneratorRepository;
use App\Repositories\QuoteRepository;
use App\Repositories\LineitemRepository;
use App\Repositories\MolliePaymentRepository;
use App\Repositories\PaypalPaymentRepository;
use App\Repositories\ProjectRepository;
use App\Repositories\RazorpayPaymentRepository;
use App\Repositories\StripePaymentRepository;
use App\Repositories\TagRepository;
use App\Repositories\TaxRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;

class Quotes extends Controller {

    /**
     * The quote repository instance.
     */
    protected $quoterepo;

    /**
     * The tags repository instance.
     */
    protected $tagrepo;

    /**
     * The user repository instance.
     */
    protected $userrepo;

    /**
     * The tax repository instance.
     */
    protected $taxrepo;

    /**
     * The unit repository instance.
     */
    protected $unitrepo;

    /**
     * The line item repository instance.
     */
    protected $lineitemrepo;

    /**
     * The event tracking repository instance.
     */
    protected $trackingrepo;

    /**
     * The event repository instance.
     */
    protected $eventrepo;

    /**
     * The emailer repository
     */
    protected $emailerrepo;

    /**
     * The quote generator repository
     */
    protected $quotegenerator;

    public function __construct(
        QuoteRepository $quoterepo,
        TagRepository $tagrepo,
        UserRepository $userrepo,
        TaxRepository $taxrepo,
        LineitemRepository $lineitemrepo,
        EventRepository $eventrepo,
        EventTrackingRepository $trackingrepo,
        EmailerRepository $emailerrepo,
        QuoteGeneratorRepository $quotegenerator
    ) {

        //core controller instantation
        parent::__construct();

        //authenticated
        $this->middleware('auth');

        $this->middleware('quotesMiddlewareIndex')->only([
            'index',
            'update',
            'store',
            'changeCategoryUpdate',
            'attachProjectUpdate',
            'dettachProject',
            'stopRecurring',
            'recurringSettingsUpdate',
        ]);

        $this->middleware('quotesMiddlewareCreate')->only([
            'create',
            'store',
        ]);

        $this->middleware('quotesMiddlewareEdit')->only([
            'edit',
            'update',
            'createClone',
            'storeClone',
            'stopRecurring',
            'dettachProject',
            'attachProject',
            'attachProjectUpdate',
            'emailClient',
            'saveQuote',
            'recurringSettings',
            'recurringSettingsUpdate',
        ]);

        $this->middleware('quotesMiddlewareShow')->only([
            'show',
            'paymentStripe',
            'downloadPDF',
        ]);

        $this->middleware('quotesMiddlewareDestroy')->only([
            'destroy',
        ]);

        //only needed for the [action] methods
        $this->middleware('quotesMiddlewareBulkEdit')->only([
            'changeCategoryUpdate',
        ]);

        $this->quoterepo = $quoterepo;
        $this->tagrepo = $tagrepo;
        $this->userrepo = $userrepo;
        $this->lineitemrepo = $lineitemrepo;
        $this->taxrepo = $taxrepo;
        $this->eventrepo = $eventrepo;
        $this->trackingrepo = $trackingrepo;
        $this->emailerrepo = $emailerrepo;
        $this->quotegenerator = $quotegenerator;

    }

    /**
     * Display a listing of quotes
     * @param object ProjectRepository instance of the repository
     * @param object CategoryRepository instance of the repository
     * @return \Illuminate\Http\Response
     */
    public function index(ProjectRepository $projectrepo, CategoryRepository $categoryrepo) {

        //default
        $projects = [];

        //get quotes
        $quotes = $this->quoterepo->search();

        //get all categories (type: quote) - for filter panel
        $categories = $categoryrepo->get('quote');

        //get all tags (type: lead) - for filter panel
        $tags = $this->tagrepo->getByType('quote');

        //refresh quotes
        foreach ($quotes as $quote) {
            $this->quoterepo->refreshQuote($quote);
        }

        //get clients project list
        if (config('visibility.filter_panel_clients_projects')) {
            if (is_numeric(request('quoteresource_id'))) {
                $projects = $projectrepo->search('', ['project_clientid' => request('quoteresource_id')]);
            }
        }

        //reponse payload
        $payload = [
            'page' => $this->pageSettings('quotes'),
            'quotes' => $quotes,
            'projects' => $projects,
            'stats' => $this->statsWidget(),
            'categories' => $categories,
            'tags' => $tags,
        ];
        
        //show the view
        return new IndexResponse($payload);
    }

    /**
     * Show the form for creating a new quote
     * @param object CategoryRepository instance of the repository
     * @return \Illuminate\Http\Response
     */
    public function createSelector() {

        $payload = [
            'title' => __('lang.add_quote_splah_title'),

        ];

        //show the form
        return new CreateSelectorResponse($payload);
    }

    /**
     * Show the form for creating a new quote
     * @param object CategoryRepository instance of the repository
     * @return \Illuminate\Http\Response
     */
    public function create(CategoryRepository $categoryrepo) {

        //quote categories
        $categories = $categoryrepo->get('quote');

        //get tags
        $tags = $this->tagrepo->getByType('quote');

        //reponse payload
        $payload = [
            'page' => $this->pageSettings('create'),
            'categories' => $categories,
            'tags' => $tags,
        ];

        //show the form
        return new CreateResponse($payload);
    }

    /**
     * Store a newly created quotein storage.
     * @param object QuoteStoreUpdate instance of the repository
     * @return \Illuminate\Http\Response
     */
    public function store(QuoteStoreUpdate $request, ClientRepository $clientrepo) {

        //are we creating a new client
        if (request('client-selection-type') == 'new') {

            //create client
            if (!$client_id = $clientrepo->create([
                'send_email' => 'yes',
                'return' => 'id',
            ])) {
                abort(409);
            }

            //add client id to request
            request()->merge([
                'bill_clientid' => $client_id,
            ]);
        }

        //create the quote
        if (!$bill_quoteid = $this->quoterepo->create()) {
            abort(409);
        }

        //add tags
        $this->tagrepo->add('quote', $bill_quoteid);

        //payloads - expense
        $this->expensesPayload($bill_quoteid);

        //reponse payload
        $payload = [
            'id' => $bill_quoteid,
        ];

        //process reponse
        return new StoreResponse($payload);
    }

    /**
     * Display the specified quote
     *  [web preview example]
     *  http://example.com/quotes/29/pdf?view=preview
     * @param  int  $id quote id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {

        //get quote object payload
        if (!$payload = $this->quotegenerator->generate($id)) {
            abort(409, __('lang.error_request_could_not_be_completed'));
        }

        //append to payload
        $payload['page'] = $this->pageSettings('quote', $payload['bill']);

        //mark events as read
        \App\Models\EventTracking::where('parent_id', $id)
            ->where('parent_type', 'quote')
            ->where('eventtracking_userid', auth()->id())
            ->update(['eventtracking_status' => 'read']);

        //if client - marked as opened
        if (auth()->user()->is_client) {
            \App\Models\Quote::where('bill_quoteid', $id)
                ->update(['bill_viewed_by_client' => 'yes']);
        }

        //custom fields
        $payload['customfields'] = \App\Models\CustomField::Where('customfields_type', 'clients')->get();

        //download pdf quote
        if (request()->segment(3) == 'pdf') {
            //render view
            return new PDFResponse($payload);
        }

        //process reponse
        return new ShowResponse($payload);
    }

    /**
     * save quote changes, when an ioice is being edited
     * @param object QuoteSave instance of the request validation
     * @param int $id quote id
     * @return array
     */
    public function saveQuote(QuoteSave $request, $id) {
        //dd($request->all());
        //get the quote
        $quotes = $this->quoterepo->search($id);
        $quote = $quotes->first();

        //save each line item in the database
        $this->quoterepo->saveLineItems($id, $quote->bill_projectid);

        //update taxes
        $this->updateQuoteTax($id);

        //update other quote attributes
        $this->quoterepo->updateQuote($id);

        //reponse payload
        $payload = [
            'quote' => $quote,
        ];

        //response
        return new SaveResponse($payload);

    }

    /**
     * update the tax for an quote
     * (1) delete existing quote taxes
     * (2) for summary taxes - save new taxes
     * (3) [future]  - calculate and save line taxes (probably should just come from the frontend, same as summary taxes)
     * @param int $bill_quoteid quote id
     * @return array
     */
    private function updateQuoteTax($bill_quoteid = '') {

        //delete current quote taxes
        \App\Models\Tax::Where('taxresource_type', 'quote')
            ->where('taxresource_id', $bill_quoteid)
            ->delete();

        //save taxes [summary taxes]
        if (is_array(request('bill_logic_taxes'))) {
            foreach (request('bill_logic_taxes') as $tax) {
                //get data elements
                $list = explode('|', $tax);
                $data = [
                    'tax_taxrateid' => $list[2],
                    'tax_name' => $list[1],
                    'tax_rate' => $list[0],
                    'taxresource_type' => 'quote',
                    'taxresource_id' => $bill_quoteid,
                ];
                $this->taxrepo->create($data);
            }
        }

    }

    /**
     * publish an quote
     * @param int $id quote id
     * @return \Illuminate\Http\Response
     */
    public function publishQuote($id) {

        //generate the quote
        if (!$payload = $this->quotegenerator->generate($id)) {
            abort(409, __('lang.error_loading_item'));
        }

        //quote
        $quote = $payload;

        //validate current status
        if ($quote['bill']->bill_status != 'draft') {
            abort(409, __('lang.quote_already_piblished'));
        }

        /** ----------------------------------------------
         * record event [comment]
         * ----------------------------------------------*/
        $resource_id = (is_numeric($quote['bill']->bill_projectid)) ? $quote['bill']->bill_projectid : $quote['bill']->bill_clientid;
        $resource_type = (is_numeric($quote['bill']->bill_projectid)) ? 'project' : 'client';
        $data = [
            'event_creatorid' => auth()->id(),
            'event_item' => 'quote',
            'event_item_id' => $quote['bill']->bill_quoteid,
            'event_item_lang' => 'event_created_quote',
            'event_item_content' => __('lang.quote') . ' - ' . $quote['bill']->formatted_bill_quoteid,
            'event_item_content2' => '',
            'event_parent_type' => 'quote',
            'event_parent_id' => $quote['bill']->bill_quoteid,
            'event_parent_title' => $quote['bill']->project_title,
            'event_clientid' => $quote['bill']->bill_clientid,
            'event_show_item' => 'yes',
            'event_show_in_timeline' => 'yes',
            'eventresource_type' => $resource_type,
            'eventresource_id' => $resource_id,
            'event_notification_category' => 'notifications_billing_activity',

        ];
        //record event
        if ($event_id = $this->eventrepo->create($data)) {
            //get users (main client)
            $users = $this->userrepo->getClientUsers($quote['bill']->bill_clientid, 'owner', 'ids');
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

        //get quote again
        $quote = \App\Models\Quote::Where('bill_quoteid', $quote['bill']->bill_quoteid)->first();

        //get new quote status and save it
        $bill_date = \Carbon\Carbon::parse($quote->bill_date);
        $bill_due_date = \Carbon\Carbon::parse($quote->bill_due_date);
        if ($bill_due_date->diffInDays(today(), false) < 0) {
            $quote->bill_status = 'due';
        } else {
            $quote->bill_status = 'overdue';
        }
        $quote->save();

        //reponse payload
        $payload = [
            'quote' => $quote,
        ];

        //response
        return new PublishResponse($payload);
    }

    /**
     * email (resend) an quote
     * @param int $id quote id
     * @return \Illuminate\Http\Response
     */
    public function resendQuote(Request $request,$id) {
        //dd($request->all());
        //generate the quote
        if (!$payload = $this->quotegenerator->generate($id)) {
            abort(409, __('lang.error_loading_item'));
        }

        //quote
        $quote = $payload;

        //validate current status
        if ($quote['bill']->bill_status == 'draft') {
            abort(409, __('lang.quote_still_draft'));
        }

        /** ----------------------------------------------
         * send email [queued]
         * ----------------------------------------------*/
        $users = $this->userrepo->getClientUsers($quote['bill']->bill_clientid, 'owner', 'collection');
        //other data
        $data["email"]=$request->input("send_email_to_client");
        $data["cc"]=$request->input("email_cc");
        foreach ($users as $user) {
            $mail = new \App\Mail\PublishQuote($user, $data, $quote);
            $mail->build();
        }

        //response
        return new ResendResponse();
    }

    /**
     * Show the form for editing the specified quote
     * @param object CategoryRepository instance of the repository
     * @param  int  $id quote id
     * @return \Illuminate\Http\Response
     */
    public function edit(CategoryRepository $categoryrepo, $id) {

        //get the project
        $quote = $this->quoterepo->search($id);

        //client categories
        $categories = $categoryrepo->get('quote');

        //get quotetags and users tags
        $tags_resource = $this->tagrepo->getByResource('quote', $id);
        $tags_user = $this->tagrepo->getByType('quote');
        $tags = $tags_resource->merge($tags_user);
        $tags = $tags->unique('tag_title');
        
        //not found
        if (!$quote = $quote->first()) {
            abort(409, __('lang.error_loading_item'));
        }

        //reponse payload
        $payload = [
            'page' => $this->pageSettings('edit'),
            'quote' => $quote,
            'categories' => $categories,
            'tags' => $tags,
        ];

        //response
        return new EditResponse($payload);
    }

    /**
     * Update the specified quotein storage.
     * @param  int  $id quote id
     * @return \Illuminate\Http\Response
     */
    public function update($id) {
        //custom error messages
        $messages = [];

        //validate
        $validator = Validator::make(request()->all(), [
            'bill_date' => 'required|date',
            'bill_due_date' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    if ($value != '' && request('bill_date') != '' && (strtotime($value) < strtotime(request('bill_date')))) {
                        return $fail(__('lang.due_date_must_be_after_start_date'));
                    }
                }],
            'bill_categoryid' => [
                'required',
                Rule::exists('categories', 'category_id'),
            ],
        ], $messages);

        //errors
        if ($validator->fails()) {
            $errors = $validator->errors();
            $messages = '';
            foreach ($errors->all() as $message) {
                $messages .= "<li>$message</li>";
            }

            abort(409, $messages);
        }

        //update
        if (!$this->quoterepo->update($id)) {
            abort(409);
        }

        //delete & update tags
        $this->tagrepo->delete('quote', $id);
        $this->tagrepo->add('quote', $id);

        //get project
        $quotes = $this->quoterepo->search($id);
        $quote = $quotes->first();

        //refresh quote
        $this->quoterepo->refreshQuote($quote);

        //reponse payload
        $payload = [
            'quotes' => $quotes,
            'source' => request('source'),
        ];

        //generate a response
        return new UpdateResponse($payload);

    }

    /**edit
     * Remove the specified quotefrom storage.
     * @param object DestroyRepository instance of the repository
     * @return \Illuminate\Http\Response
     */
    public function destroy(DestroyRepository $destroyrepo) {

        //delete each record in the array
        $allrows = array();
        foreach (request('ids') as $id => $value) {
            //only checked items
            if ($value == 'on') {
                //delete file
                $destroyrepo->destroyQuote($id);
                //add to array
                $allrows[] = $id;
            }
        }
        //reponse payload
        $payload = [
            'allrows' => $allrows,
            'stats' => $this->statsWidget(),
        ];

        //generate a response
        return new DestroyResponse($payload);
    }

    /**
     * Show the form for updating the quote
     * @param object CategoryRepository instance of the repository
     * @return \Illuminate\Http\Response
     */
    public function changeCategory(CategoryRepository $categoryrepo) {

        //get all quote categories
        $categories = $categoryrepo->get('quote');

        //reponse payload
        $payload = [
            'categories' => $categories,
        ];

        //show the form
        return new ChangeCategoryResponse($payload);
    }

    /**
     * Show the form for updating the quote
     * @param object CategoryRepository instance of the repository
     * @return \Illuminate\Http\Response
     */
    public function changeCategoryUpdate(CategoryRepository $categoryrepo) {

        //validate the category exists
        if (!\App\Models\Category::Where('category_id', request('category'))
            ->Where('category_type', 'quote')
            ->first()) {
            abort(409, __('lang.category_not_found'));
        }

        //update each quote
        $allrows = array();
        foreach (request('ids') as $bill_quoteid => $value) {
            if ($value == 'on') {
                $quote = \App\Models\Quote::Where('bill_quoteid', $bill_quoteid)->first();
                //update the category
                $quote->bill_categoryid = request('category');
                $quote->save();
                //get the quote in rendering friendly format
                $quotes = $this->quoterepo->search($bill_quoteid);
                //add to array
                $allrows[] = $quotes;
            }
        }

        //reponse payload
        $payload = [
            'allrows' => $allrows,
        ];

        //show the form
        return new ChangeCategoryUpdateResponse($payload);
    }

    /**
     * Show the form for attaching a project to an quote
     * @return \Illuminate\Http\Response
     */
    public function attachProject() {

        //get client id
        $client_id = request('client_id');

        //reponse payload
        $payload = [
            'projects_feed_url' => url("/feed/projects?ref=clients_projects&client_id=$client_id"),
        ];

        //show the form
        return new AttachProjectResponse($payload);
    }

    /**
     * attach a project to an quote
     * @return \Illuminate\Http\Response
     */
    public function attachProjectUpdate() {

        //validate the quote exists
        $quote = \App\Models\Quote::Where('bill_quoteid', request()->route('quote'))->first();

        //validate the project exists
        if (!$project = \App\Models\Project::Where('project_id', request('attach_project_id'))->first()) {
            abort(409, __('lang.project_not_found'));
        }

        //update the quote
        $quote->bill_projectid = request('attach_project_id');
        $quote->bill_clientid = $project->project_clientid;
        $quote->save();

        //get refreshed quote
        $quotes = $this->quoterepo->search(request()->route('quote'));
        $quote = $quotes->first();

        //get all payments and add project
        if ($payments = \App\Models\Payment::Where('payment_quoteid', request()->route('quote'))->get()) {
            foreach ($payments as $payment) {
                $payment->payment_projectid = request('attach_project_id');
                $payment->save();
            }
        }

        //refresh quote
        $this->quoterepo->refreshQuote($quote);

        //reponse payload
        $payload = [
            'quotes' => $quotes,
        ];

        //show the form
        return new UpdateResponse($payload);
    }

    /**
     * dettach quote from a project
     * @return \Illuminate\Http\Response
     */
    public function dettachProject() {

        //validate the quote exists
        $quote = \App\Models\Quote::Where('bill_quoteid', request()->route('quote'))->first();

        //update the quote
        $quote->bill_projectid = null;
        $quote->save();

        //get refreshed quote
        $quotes = $this->quoterepo->search(request()->route('quote'));

        //get all payments and remove project
        if ($payments = \App\Models\Payment::Where('payment_quoteid', request()->route('quote'))->get()) {
            foreach ($payments as $payment) {
                $payment->payment_projectid = null;
                $payment->save();
            }
        }

        //reponse payload
        $payload = [
            'quotes' => $quotes,
        ];

        //show the form
        return new UpdateResponse($payload);
    }

    /**
     * show the form for cloning an quote
     * @param object CategoryRepository instance of the repository
     * @return \Illuminate\Http\Response
     */
    public function createClone(CategoryRepository $categoryrepo, $id) {

        //get the quote
        $quote = \App\Models\Quote::Where('bill_quoteid', $id)->first();

        //get tags
        $tags = $this->tagrepo->getByType('quote');

        //quote categories
        $categories = $categoryrepo->get('quote');

        //reponse payload
        $payload = [
            'quote' => $quote,
            'tags' => $tags,
            'categories' => $categories,
        ];

        //show the form
        return new CreateCloneResponse($payload);
    }

    /**
     * show the form for cloning an quote
     * @param object QuoteClone instance of the request validation
     * @param object CloneQuoteRepository instance of the repository
     * @return \Illuminate\Http\Response
     */
    public function storeClone(QuoteClone $request, CloneQuoteRepository $clonequoterepo, $id) {

        //get the quote
        $quote = \App\Models\Quote::Where('bill_quoteid', $id)->first();

        //clone data
        $data = [
            'quote_id' => $id,
            'client_id' => request('bill_clientid'),
            'project_id' => request('bill_projectid'),
            'quote_date' => request('bill_date'),
            'quote_due_date' => request('bill_due_date'),
            'return' => 'id',
        ];

        //clone quote
        if (!$quote_id = $clonequoterepo->clone($data)) {
            abort(409, __('lang.cloning_failed'));
        }

        //reponse payload
        $payload = [
            'id' => $quote_id,
        ];

        //show the form
        return new StoreCloneResponse($payload);
    }

    /**
     * email a pdf versio to the client
     * @return \Illuminate\Http\Response
     */
    public function emailClient() {

        //validate the quote exists
        $quote = \App\Models\Quote::Where('bill_quoteid', request('id'))->first();

        //notice
        $jsondata['notification'] = array('type' => 'success', 'value' => '[TODO]');

        //response
        return response()->json($jsondata);
    }

    /**
     * Show the form for editing the specified quote
     * @param  int  $quote quote id
     * @return \Illuminate\Http\Response
     */
    public function recurringSettings($id) {

        //get the project
        $quote = \App\Models\Quote::Where('bill_quoteid', $id)->first();

        //reponse payload
        $payload = [
            'page' => $this->pageSettings('edit'),
            'quote' => $quote,
        ];

        //response
        return new RecurringSettingsResponse($payload);
    }

    /**
     * Update recurring settings
     * @param object QuoteRecurrringSettings instance of the request validation object
     * @param  int  $quote quote id
     * @return \Illuminate\Http\Response
     */
    public function recurringSettingsUpdate(QuoteRecurrringSettings $request, $id) {

        //get project
        $quotes = $this->quoterepo->search($id);
        $quote = $quotes->first();

        //update
        $quote->bill_recurring = 'yes';
        $quote->bill_recurring_duration = request('bill_recurring_duration');
        $quote->bill_recurring_period = request('bill_recurring_period');
        $quote->bill_recurring_cycles = request('bill_recurring_cycles');
        $quote->bill_recurring_next = request('bill_recurring_next');

        //refresh quote
        $this->quoterepo->refreshQuote($quote);

        //reponse payload
        $payload = [
            'page' => $this->pageSettings('edit'),
            'quotes' => $quotes,
        ];

        //response
        return new UpdateResponse($payload);
    }

    /**
     * stop an quote from recurring
     * @return \Illuminate\Http\Response
     */
    public function stopRecurring() {

        //get the quote
        $quote = \App\Models\Quote::Where('bill_quoteid', request()->route('quote'))->first();

        //update the quote
        $quote->bill_recurring = 'no';
        $quote->bill_recurring_duration = null;
        $quote->bill_recurring_period = null;
        $quote->bill_recurring_cycles = null;
        $quote->bill_recurring_next = null;
        $quote->save();

        //get refreshed quote
        $quotes = $this->quoterepo->search(request()->route('quote'));

        //reponse payload
        $payload = [
            'quotes' => $quotes,
        ];

        //show the form
        return new UpdateResponse($payload);
    }

    /**
     * create line items for ths quote (from submitted expense items)
     * @param int $bill_quoteid quote id
     * @return null
     */
    public function expensesPayload($bill_quoteid) {

        $quote = \App\Models\Quote::Where('bill_quoteid', $bill_quoteid)->first();

        //do we have an expense in the payload?
        if (is_array(request('expense_payload'))) {
            foreach (request('expense_payload') as $expense_id) {
                //get the expense
                if ($expense = \App\Models\Expense::Where('expense_id', $expense_id)->first()) {

                    //create a new quote line item
                    $data['lineitem_description'] = $expense->expense_description;
                    $data['lineitem_rate'] = $expense->expense_amount;
                    $data['lineitem_unit'] = __('lang.item');
                    $data['lineitem_quantity'] = 1;
                    $data['lineitem_total'] = $expense->expense_amount;
                    $data['lineitemresource_linked_type'] = 'expense';
                    $data['lineitemresource_linked_type'] = $expense_id;
                    $data['lineitemresource_type'] = 'quote';
                    $data['lineitemresource_id'] = $bill_quoteid;
                    $this->lineitemrepo->create($data);

                    //update expense with quote id and mark as quoted
                    $expense->expense_billing_status = 'quoted';
                    $expense->expense_billable_quoteid = $bill_quoteid;
                    $expense->save();
                }
            }
        }
    }

    /**
     * create line items for ths quote (from submitted expense items)
     * @param object StripePaymentRepository instance of the repository
     * @param object QuoteRepository instance of the repository
     * @param int $id client id
     */
    public function paymentStripe(StripePaymentRepository $striperepo, QuoteRepository $quoterepo, $id) {

        //get quote
        $quotes = $quoterepo->search($id);
        $quote = $quotes->first();

        //payment payload
        $data = [
            'amount' => $quote->quote_balance,
            'currency' => config('system.settings_stripe_currency'),
            'quote_id' => $quote->bill_quoteid,
            'cancel_url' => url('quotes/' . $quote->bill_quoteid), //in future, this can be bulk payments page
        ];

        //create a new stripe session
        $session_id = $striperepo->onetimePayment($data);

        //reponse payload
        $payload = [
            'session_id' => $session_id,
        ];

        //show the view
        return new StripePaymentResponse($payload);
    }

    /**
     * create line items for ths quote (from submitted expense items)
     * @param object PaypalPaymentRepository instance of the repository
     * @param object QuoteRepository instance of the repository
     * @param int $id client id
     */
    public function paymentPaypal(PaypalPaymentRepository $paypalrepo, QuoteRepository $quoterepo, $id) {

        //get quote
        $quotes = $quoterepo->search($id);
        $quote = $quotes->first();

        //payment payload
        $data = [
            'amount' => $quote->quote_balance,
            'currency' => config('system.settings_paypal_currency'),
            'item_name' => __('lang.quote_payment'),
            'quote_id' => $quote->bill_quoteid,
            'ipn_url' => url('/api/paypal/ipn'),
            'cancel_url' => url('quotes/' . $quote->bill_quoteid), //in future, this can be bulk payments page
        ];

        //create a new paypal session
        $session_id = $paypalrepo->onetimePayment($data);

        //more data
        $data['thank_you_url'] = url('payments/thankyou?session_id=' . $session_id);
        $data['session_id'] = $session_id;

        //reponse payload
        $payload = [
            'paypal' => $data,
        ];

        //show the view
        return new PaypalPaymentResponse($payload);
    }

    /**
     * create line items for ths quote (from submitted expense items)
     * @param object PaypalPaymentRepository instance of the repository
     * @param object QuoteRepository instance of the repository
     * @param int $id client id
     */
    public function paymentRazorpay(RazorpayPaymentRepository $razorpayrepo, QuoteRepository $quoterepo, $id) {

        //get quote
        $quotes = $quoterepo->search($id);
        $quote = $quotes->first();

        //payment payload
        $payload = [
            'amount' => $quote->quote_balance,
            'unit_amount' => $quote->quote_balance * 100, //lowest unit (e.g. cents)
            'currency' => config('system.settings_razorpay_currency'),
            'quote_id' => $quote->bill_quoteid,
        ];

        //create a new razorpay session
        if (!$order_id = $razorpayrepo->onetimePayment($payload)) {
            abort(409, __('lang.error_request_could_not_be_completed'));
        }

        //more data
        $payload['thank_you_url'] = url('payments/thankyou?gateway=razorpay&order_id=' . $order_id);
        $payload['order_id'] = $order_id;
        $payload['company_name'] = config('system.settings_company_name');
        $payload['description'] = __('lang.quote_payment');
        $payload['image'] = runtimeLogoSmall();
        $payload['thankyou_url'] = url('/payments/thankyou/razorpay');
        $payload['client_name'] = $quote->client_company_name;
        $payload['client_email'] = auth()->user()->email;
        $payload['key'] = config('system.settings_razorpay_keyid');

        //show the view
        return new RazorpayPaymentResponse($payload);
    }

    /**
     * create line items for ths quote (from submitted expense items)
     * @param object PaypalPaymentRepository instance of the repository
     * @param object QuoteRepository instance of the repository
     * @param int $id client id
     */
    public function paymentMollie(MolliePaymentRepository $mollierepo, QuoteRepository $quoterepo, $id) {

        //get quote
        $quotes = $quoterepo->search($id);
        $quote = $quotes->first();

        //payment payload
        $payload = [
            'amount' => $quote->quote_balance,
            'currency' => config('system.settings_mollie_currency'),
            'quote_id' => $quote->bill_quoteid,
            'thank_you_url' => url('payments/thankyou?gateway=mollie'),
            'webhooks_url' => url('/api/mollie/webhooks'),
        ];

        //create a new razorpay session
        if (!$mollie_url = $mollierepo->onetimePayment($payload)) {
            abort(409, __('lang.error_request_could_not_be_completed'));
        }

        //payload
        $payload = [
            'redirect_url' => $mollie_url,
        ];

        //redirect user
        return new MolliePaymentResponse($payload);
    }

    /**
     * basic settings for the quotes list page
     * @return array
     */
    private function pageSettings($section = '', $data = array()) {

        //common settings
        $page = [
            'crumbs' => [
                __('lang.sales'),
                __('lang.quotes'),
            ],
            'crumbs_special_class' => 'list-pages-crumbs',
            'page' => 'quotes',
            'no_results_message' => __('lang.no_results_found'),
            'mainmenu_quotes' => 'active',
            'mainmenu_sales' => 'active',
            'submenu_quotes' => 'active',
            'sidepanel_id' => 'sidepanel-filter-quotes',
            'dynamic_search_url' => url('quotes/search?action=search&quoteresource_id=' . request('quoteresource_id') . '&quoteresource_type=' . request('quoteresource_type')),
            'add_button_classes' => 'add-edit-quote-button',
            'load_more_button_route' => 'quotes',
            'source' => 'list',
        ];
        //default modal settings (modify for sepecif sections)
        $page += [
            'add_modal_title' => __('lang.add_quote'),
            'add_modal_create_url' => url('quotes/create?quoteresource_id=' . request('quoteresource_id') . '&quoteresource_type=' . request('quoteresource_type')),
            'add_modal_action_url' => url('quotes?quoteresource_id=' . request('quoteresource_id') . '&quoteresource_type=' . request('quoteresource_type')),
            'add_modal_action_ajax_class' => '',
            'add_modal_action_ajax_loading_target' => 'commonModalBody',
            'add_modal_action_method' => 'POST',
        ];

        //quotes list page
        if ($section == 'quotes') {
            $page += [
                'meta_title' => __('lang.quotes'),
                'heading' => __('lang.quotes'),
                'sidepanel_id' => 'sidepanel-filter-quotes',
            ];
            if (request('source') == 'ext') {
                $page += [
                    'list_page_actions_size' => 'col-lg-12',
                ];
            }
            return $page;
        }

        //quote page
        if ($section == 'quote') {
            //adjust
            $page['page'] = 'quote';
            //add
            $page += [
                'crumbs' => [
                    __('lang.quote'),
                ],
                'crumbs_special_class' => 'main-pages-crumbs',
                'meta_title' => __('lang.quote') . ' #' . $data->formatted_bill_quoteid,
                'heading' => __('lang.project') . ' - ' . $data->project_title,
                'bill_quoteid' => request()->segment(2),
                'source_for_filter_panels' => 'ext',
                'section' => 'overview',
            ];

            //crumbs
            $page['crumbs'] = [
                __('lang.sales'),
                __('lang.quotes'),
                $data['formatted_bill_quoteid'],
            ];

            //ajax loading and tabs
            return $page;
        }

        //create new resource
        if ($section == 'create') {
            $page += [
                'section' => 'create',
            ];
            return $page;
        }

        //edit new resource
        if ($section == 'edit') {
            $page['mode'] = 'editing';
            $page += [
                'section' => 'edit',
            ];
            return $page;
        }

        //return
        return $page;
    }

    /**
     * data for the stats widget
     * @return array
     */
    private function statsWidget($data = array()) {

        //stats
        $count_all = $this->quoterepo->search('', ['stats' => 'count-all']);
        $count_due = $this->quoterepo->search('', ['stats' => 'count-due']);
        $count_overdue = $this->quoterepo->search('', ['stats' => 'count-overdue']);

        $sum_all = $this->quoterepo->search('', ['stats' => 'sum-all']);
        $sum_payments = $this->quoterepo->search('', ['stats' => 'sum-payments']);
        $sum_due_balances = $this->quoterepo->search('', ['stats' => 'sum-due-balances']);
        $sum_overdue_balances = $this->quoterepo->search('', ['stats' => 'sum-overdue-balances']);

        //default values
        $stats = [
            [
                'value' => runtimeMoneyFormat($sum_all),
                'title' => __('lang.quotes') . " ($count_all)",
                'percentage' => '100%',
                'color' => 'bg-info',
            ],
            [
                'value' => runtimeMoneyFormat($sum_payments),
                'title' => __('lang.payments'),
                'percentage' => '100%',
                'color' => 'bg-success',
            ],
            [
                'value' => runtimeMoneyFormat($sum_due_balances),
                'title' => __('lang.due') . " ($count_due)",
                'percentage' => '100%',
                'color' => 'bg-warning',
            ],
            [
                'value' => runtimeMoneyFormat($sum_overdue_balances),
                'title' => __('lang.overdue') . " ($count_overdue)",
                'percentage' => '100%',
                'color' => 'bg-danger',
            ],
        ];
        //return
        return $stats;
    }
}