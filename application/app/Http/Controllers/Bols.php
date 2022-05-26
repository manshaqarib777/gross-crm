<?php

/** --------------------------------------------------------------------------------
 * This controller manages all the business logic for bols
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Bols\BolClone;
use App\Http\Requests\Bols\BolRecurrringSettings;
use App\Http\Requests\Bols\BolSave;
use App\Http\Requests\Bols\BolStoreUpdate;
use App\Http\Responses\Common\ChangeCategoryResponse;
use App\Http\Responses\Bols\AttachProjectResponse;
use App\Http\Responses\Bols\ChangeCategoryUpdateResponse;
use App\Http\Responses\Bols\CreateCloneResponse;
use App\Http\Responses\Bols\CreateResponse;
use App\Http\Responses\Bols\DestroyResponse;
use App\Http\Responses\Bols\EditResponse;
use App\Http\Responses\Bols\IndexResponse;
use App\Http\Responses\Bols\PDFResponse;
use App\Http\Responses\Bols\PublishResponse;
use App\Http\Responses\Bols\RecurringSettingsResponse;
use App\Http\Responses\Bols\ResendResponse;
use App\Http\Responses\Bols\SaveResponse;
use App\Http\Responses\Bols\ShowResponse;
use App\Http\Responses\Bols\StoreCloneResponse;
use App\Http\Responses\Bols\StoreResponse;
use App\Http\Responses\Bols\UpdateResponse;
use App\Http\Responses\Pay\MolliePaymentResponse;
use App\Http\Responses\Pay\PaypalPaymentResponse;
use App\Http\Responses\Pay\RazorpayPaymentResponse;
use App\Http\Responses\Pay\StripePaymentResponse;
use App\Repositories\CategoryRepository;
use App\Repositories\ClientRepository;
use App\Repositories\CloneBolRepository;
use App\Repositories\DestroyRepository;
use App\Repositories\EmailerRepository;
use App\Repositories\EventRepository;
use App\Repositories\EventTrackingRepository;
use App\Repositories\BolGeneratorRepository;
use App\Repositories\BolRepository;
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

class Bols extends Controller {

    /**
     * The bol repository instance.
     */
    protected $bolrepo;

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
     * The bol generator repository
     */
    protected $bolgenerator;

    public function __construct(
        BolRepository $bolrepo,
        TagRepository $tagrepo,
        UserRepository $userrepo,
        TaxRepository $taxrepo,
        LineitemRepository $lineitemrepo,
        EventRepository $eventrepo,
        EventTrackingRepository $trackingrepo,
        EmailerRepository $emailerrepo,
        BolGeneratorRepository $bolgenerator
    ) {

        //core controller instantation
        parent::__construct();

        //authenticated
        $this->middleware('auth');

        $this->middleware('bolsMiddlewareIndex')->only([
            'index',
            'update',
            'store',
            'changeCategoryUpdate',
            'attachProjectUpdate',
            'dettachProject',
            'stopRecurring',
            'recurringSettingsUpdate',
        ]);

        $this->middleware('bolsMiddlewareCreate')->only([
            'create',
            'store',
        ]);

        $this->middleware('bolsMiddlewareEdit')->only([
            'edit',
            'update',
            'createClone',
            'storeClone',
            'stopRecurring',
            'dettachProject',
            'attachProject',
            'attachProjectUpdate',
            'emailClient',
            'saveBol',
            'recurringSettings',
            'recurringSettingsUpdate',
        ]);

        $this->middleware('bolsMiddlewareShow')->only([
            'show',
            'paymentStripe',
            'downloadPDF',
        ]);

        $this->middleware('bolsMiddlewareDestroy')->only([
            'destroy',
        ]);

        //only needed for the [action] methods
        $this->middleware('bolsMiddlewareBulkEdit')->only([
            'changeCategoryUpdate',
        ]);

        $this->bolrepo = $bolrepo;
        $this->tagrepo = $tagrepo;
        $this->userrepo = $userrepo;
        $this->lineitemrepo = $lineitemrepo;
        $this->taxrepo = $taxrepo;
        $this->eventrepo = $eventrepo;
        $this->trackingrepo = $trackingrepo;
        $this->emailerrepo = $emailerrepo;
        $this->bolgenerator = $bolgenerator;

    }

    /**
     * Display a listing of bols
     * @param object ProjectRepository instance of the repository
     * @param object CategoryRepository instance of the repository
     * @return \Illuminate\Http\Response
     */
    public function index(ProjectRepository $projectrepo, CategoryRepository $categoryrepo) {

        //default
        $projects = [];

        //get bols
        $bols = $this->bolrepo->search();

        //get all categories (type: bol) - for filter panel
        $categories = $categoryrepo->get('bol');

        //get all tags (type: lead) - for filter panel
        $tags = $this->tagrepo->getByType('bol');

        //refresh bols
        foreach ($bols as $bol) {
            $this->bolrepo->refreshBol($bol);
        }

        //get clients project list
        if (config('visibility.filter_panel_clients_projects')) {
            if (is_numeric(request('bolresource_id'))) {
                $projects = $projectrepo->search('', ['project_clientid' => request('bolresource_id')]);
            }
        }

        //reponse payload
        $payload = [
            'page' => $this->pageSettings('bols'),
            'bols' => $bols,
            'projects' => $projects,
            'stats' => $this->statsWidget(),
            'categories' => $categories,
            'tags' => $tags,
        ];
        //show the view
        return new IndexResponse($payload);
    }

    /**
     * Show the form for creating a new bol
     * @param object CategoryRepository instance of the repository
     * @return \Illuminate\Http\Response
     */
    public function createSelector() {

        $payload = [
            'title' => __('lang.add_bol_splah_title'),

        ];

        //show the form
        return new CreateSelectorResponse($payload);
    }

    /**
     * Show the form for creating a new bol
     * @param object CategoryRepository instance of the repository
     * @return \Illuminate\Http\Response
     */
    public function create(CategoryRepository $categoryrepo) {

        //bol categories
        $categories = $categoryrepo->get('bol');

        //get tags
        $tags = $this->tagrepo->getByType('bol');

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
     * Store a newly created bolin storage.
     * @param object BolStoreUpdate instance of the repository
     * @return \Illuminate\Http\Response
     */
    public function store(BolStoreUpdate $request, ClientRepository $clientrepo) {

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

        //create the bol
        if (!$bill_bolid = $this->bolrepo->create()) {
            abort(409);
        }

        //add tags
        $this->tagrepo->add('bol', $bill_bolid);

        //payloads - expense
        $this->expensesPayload($bill_bolid);

        //reponse payload
        $payload = [
            'id' => $bill_bolid,
        ];

        //process reponse
        return new StoreResponse($payload);
    }

    /**
     * Display the specified bol
     *  [web preview example]
     *  http://example.com/bols/29/pdf?view=preview
     * @param  int  $id bol id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {

        //get bol object payload
        if (!$payload = $this->bolgenerator->generate($id)) {
            abort(409, __('lang.error_request_could_not_be_completed'));
        }

        //append to payload
        $payload['page'] = $this->pageSettings('bol', $payload['bill']);

        //mark events as read
        \App\Models\EventTracking::where('parent_id', $id)
            ->where('parent_type', 'bol')
            ->where('eventtracking_userid', auth()->id())
            ->update(['eventtracking_status' => 'read']);

        //if client - marked as opened
        if (auth()->user()->is_client) {
            \App\Models\Bol::where('bill_bolid', $id)
                ->update(['bill_viewed_by_client' => 'yes']);
        }

        //custom fields
        $payload['customfields'] = \App\Models\CustomField::Where('customfields_type', 'clients')->get();

        //download pdf bol
        if (request()->segment(3) == 'pdf') {
            //render view
            return new PDFResponse($payload);
        }

        //process reponse
        return new ShowResponse($payload);
    }

    /**
     * save bol changes, when an ioice is being edited
     * @param object BolSave instance of the request validation
     * @param int $id bol id
     * @return array
     */
    public function saveBol(BolSave $request, $id) {
        //dd($request->all());
        //get the bol
        $bols = $this->bolrepo->search($id);
        $bol = $bols->first();

        //save each line item in the database
        $this->bolrepo->saveLineItems($id, $bol->bill_projectid);

        //update taxes
        $this->updateBolTax($id);

        //update other bol attributes
        $this->bolrepo->updateBol($id);

        //reponse payload
        $payload = [
            'bol' => $bol,
        ];

        //response
        return new SaveResponse($payload);

    }

    /**
     * update the tax for an bol
     * (1) delete existing bol taxes
     * (2) for summary taxes - save new taxes
     * (3) [future]  - calculate and save line taxes (probably should just come from the frontend, same as summary taxes)
     * @param int $bill_bolid bol id
     * @return array
     */
    private function updateBolTax($bill_bolid = '') {

        //delete current bol taxes
        \App\Models\Tax::Where('taxresource_type', 'bol')
            ->where('taxresource_id', $bill_bolid)
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
                    'taxresource_type' => 'bol',
                    'taxresource_id' => $bill_bolid,
                ];
                $this->taxrepo->create($data);
            }
        }

    }

    /**
     * publish an bol
     * @param int $id bol id
     * @return \Illuminate\Http\Response
     */
    public function publishBol($id) {

        //generate the bol
        if (!$payload = $this->bolgenerator->generate($id)) {
            abort(409, __('lang.error_loading_item'));
        }

        //bol
        $bol = $payload;

        //validate current status
        if ($bol["bill"]->bill_status != 'draft') {
            abort(409, __('lang.bol_already_piblished'));
        }

        /** ----------------------------------------------
         * record event [comment]
         * ----------------------------------------------*/
        $resource_id = (is_numeric($bol["bill"]->bill_projectid)) ? $bol["bill"]->bill_projectid : $bol["bill"]->bill_clientid;
        $resource_type = (is_numeric($bol["bill"]->bill_projectid)) ? 'project' : 'client';
        $data = [
            'event_creatorid' => auth()->id(),
            'event_item' => 'bol',
            'event_item_id' => $bol["bill"]->bill_bolid,
            'event_item_lang' => 'event_created_bol',
            'event_item_content' => __('lang.bol') . ' - ' . $bol["bill"]->formatted_bill_bolid,
            'event_item_content2' => '',
            'event_parent_type' => 'bol',
            'event_parent_id' => $bol["bill"]->bill_bolid,
            'event_parent_title' => $bol["bill"]->project_title,
            'event_clientid' => $bol["bill"]->bill_clientid,
            'event_show_item' => 'yes',
            'event_show_in_timeline' => 'yes',
            'eventresource_type' => $resource_type,
            'eventresource_id' => $resource_id,
            'event_notification_category' => 'notifications_billing_activity',

        ];
        //record event
        if ($event_id = $this->eventrepo->create($data)) {
            //get users (main client)
            $users = $this->userrepo->getClientUsers($bol["bill"]->bill_clientid, 'owner', 'ids');
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

        //get bol again
        $bol = \App\Models\Bol::Where('bill_bolid', $bol["bill"]->bill_bolid)->first();

        //get new bol status and save it
        $bill_date = \Carbon\Carbon::parse($bol->bill_date);
        $bill_due_date = \Carbon\Carbon::parse($bol->bill_due_date);
        if ($bill_due_date->diffInDays(today(), false) < 0) {
            $bol->bill_status = 'due';
        } else {
            $bol->bill_status = 'overdue';
        }
        $bol->save();

        //reponse payload
        $payload = [
            'bol' => $bol,
        ];

        //response
        return new PublishResponse($payload);
    }

    /**
     * email (resend) an bol
     * @param int $id bol id
     * @return \Illuminate\Http\Response
     */
    public function resendBol(Request $request,$id) {
        //dd($request->all());
        //generate the bol
        if (!$payload = $this->bolgenerator->generate($id)) {
            abort(409, __('lang.error_loading_item'));
        }

        //bol
        $bol = $payload;

        //validate current status
        if ($bol["bill"]->bill_status == 'draft') {
            abort(409, __('lang.bol_still_draft'));
        }

        /** ----------------------------------------------
         * send email [queued]
         * ----------------------------------------------*/
        $users = $this->userrepo->getClientUsers($bol["bill"]->bill_clientid, 'owner', 'collection');
        //other data
        $data["email"]=$request->input("send_email_to_client");
        $data["cc"]=$request->input("email_cc");
        foreach ($users as $user) {
            $mail = new \App\Mail\PublishBol($user, $data, $bol);
            $mail->build();
        }

        //response
        return new ResendResponse();
    }

    /**
     * Show the form for editing the specified bol
     * @param object CategoryRepository instance of the repository
     * @param  int  $id bol id
     * @return \Illuminate\Http\Response
     */
    public function edit(CategoryRepository $categoryrepo, $id) {

        //get the project
        $bol = $this->bolrepo->search($id);

        //client categories
        $categories = $categoryrepo->get('bol');

        //get boltags and users tags
        $tags_resource = $this->tagrepo->getByResource('bol', $id);
        $tags_user = $this->tagrepo->getByType('bol');
        $tags = $tags_resource->merge($tags_user);
        $tags = $tags->unique('tag_title');
        
        //not found
        if (!$bol = $bol->first()) {
            abort(409, __('lang.error_loading_item'));
        }

        //reponse payload
        $payload = [
            'page' => $this->pageSettings('edit'),
            'bol' => $bol,
            'categories' => $categories,
            'tags' => $tags,
        ];

        //response
        return new EditResponse($payload);
    }

    /**
     * Update the specified bolin storage.
     * @param  int  $id bol id
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
        if (!$this->bolrepo->update($id)) {
            abort(409);
        }

        //delete & update tags
        $this->tagrepo->delete('bol', $id);
        $this->tagrepo->add('bol', $id);

        //get project
        $bols = $this->bolrepo->search($id);
        $bol = $bols->first();

        //refresh bol
        $this->bolrepo->refreshBol($bol);

        //reponse payload
        $payload = [
            'bols' => $bols,
            'source' => request('source'),
        ];

        //generate a response
        return new UpdateResponse($payload);

    }

    /**edit
     * Remove the specified bolfrom storage.
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
                $destroyrepo->destroyBol($id);
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
     * Show the form for updating the bol
     * @param object CategoryRepository instance of the repository
     * @return \Illuminate\Http\Response
     */
    public function changeCategory(CategoryRepository $categoryrepo) {

        //get all bol categories
        $categories = $categoryrepo->get('bol');

        //reponse payload
        $payload = [
            'categories' => $categories,
        ];

        //show the form
        return new ChangeCategoryResponse($payload);
    }

    /**
     * Show the form for updating the bol
     * @param object CategoryRepository instance of the repository
     * @return \Illuminate\Http\Response
     */
    public function changeCategoryUpdate(CategoryRepository $categoryrepo) {

        //validate the category exists
        if (!\App\Models\Category::Where('category_id', request('category'))
            ->Where('category_type', 'bol')
            ->first()) {
            abort(409, __('lang.category_not_found'));
        }

        //update each bol
        $allrows = array();
        foreach (request('ids') as $bill_bolid => $value) {
            if ($value == 'on') {
                $bol = \App\Models\Bol::Where('bill_bolid', $bill_bolid)->first();
                //update the category
                $bol->bill_categoryid = request('category');
                $bol->save();
                //get the bol in rendering friendly format
                $bols = $this->bolrepo->search($bill_bolid);
                //add to array
                $allrows[] = $bols;
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
     * Show the form for attaching a project to an bol
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
     * attach a project to an bol
     * @return \Illuminate\Http\Response
     */
    public function attachProjectUpdate() {

        //validate the bol exists
        $bol = \App\Models\Bol::Where('bill_bolid', request()->route('bol'))->first();

        //validate the project exists
        if (!$project = \App\Models\Project::Where('project_id', request('attach_project_id'))->first()) {
            abort(409, __('lang.project_not_found'));
        }

        //update the bol
        $bol->bill_projectid = request('attach_project_id');
        $bol->bill_clientid = $project->project_clientid;
        $bol->save();

        //get refreshed bol
        $bols = $this->bolrepo->search(request()->route('bol'));
        $bol = $bols->first();

        //get all payments and add project
        if ($payments = \App\Models\Payment::Where('payment_bolid', request()->route('bol'))->get()) {
            foreach ($payments as $payment) {
                $payment->payment_projectid = request('attach_project_id');
                $payment->save();
            }
        }

        //refresh bol
        $this->bolrepo->refreshBol($bol);

        //reponse payload
        $payload = [
            'bols' => $bols,
        ];

        //show the form
        return new UpdateResponse($payload);
    }

    /**
     * dettach bol from a project
     * @return \Illuminate\Http\Response
     */
    public function dettachProject() {

        //validate the bol exists
        $bol = \App\Models\Bol::Where('bill_bolid', request()->route('bol'))->first();

        //update the bol
        $bol->bill_projectid = null;
        $bol->save();

        //get refreshed bol
        $bols = $this->bolrepo->search(request()->route('bol'));

        //get all payments and remove project
        if ($payments = \App\Models\Payment::Where('payment_bolid', request()->route('bol'))->get()) {
            foreach ($payments as $payment) {
                $payment->payment_projectid = null;
                $payment->save();
            }
        }

        //reponse payload
        $payload = [
            'bols' => $bols,
        ];

        //show the form
        return new UpdateResponse($payload);
    }

    /**
     * show the form for cloning an bol
     * @param object CategoryRepository instance of the repository
     * @return \Illuminate\Http\Response
     */
    public function createClone(CategoryRepository $categoryrepo, $id) {

        //get the bol
        $bol = \App\Models\Bol::Where('bill_bolid', $id)->first();

        //get tags
        $tags = $this->tagrepo->getByType('bol');

        //bol categories
        $categories = $categoryrepo->get('bol');

        //reponse payload
        $payload = [
            'bol' => $bol,
            'tags' => $tags,
            'categories' => $categories,
        ];

        //show the form
        return new CreateCloneResponse($payload);
    }

    /**
     * show the form for cloning an bol
     * @param object BolClone instance of the request validation
     * @param object CloneBolRepository instance of the repository
     * @return \Illuminate\Http\Response
     */
    public function storeClone(BolClone $request, CloneBolRepository $clonebolrepo, $id) {

        //get the bol
        $bol = \App\Models\Bol::Where('bill_bolid', $id)->first();

        //clone data
        $data = [
            'bol_id' => $id,
            'client_id' => request('bill_clientid'),
            'project_id' => request('bill_projectid'),
            'bol_date' => request('bill_date'),
            'bol_due_date' => request('bill_due_date'),
            'return' => 'id',
        ];

        //clone bol
        if (!$bol_id = $clonebolrepo->clone($data)) {
            abort(409, __('lang.cloning_failed'));
        }

        //reponse payload
        $payload = [
            'id' => $bol_id,
        ];

        //show the form
        return new StoreCloneResponse($payload);
    }

    /**
     * email a pdf versio to the client
     * @return \Illuminate\Http\Response
     */
    public function emailClient() {

        //validate the bol exists
        $bol = \App\Models\Bol::Where('bill_bolid', request('id'))->first();

        //notice
        $jsondata['notification'] = array('type' => 'success', 'value' => '[TODO]');

        //response
        return response()->json($jsondata);
    }

    /**
     * Show the form for editing the specified bol
     * @param  int  $bol bol id
     * @return \Illuminate\Http\Response
     */
    public function recurringSettings($id) {

        //get the project
        $bol = \App\Models\Bol::Where('bill_bolid', $id)->first();

        //reponse payload
        $payload = [
            'page' => $this->pageSettings('edit'),
            'bol' => $bol,
        ];

        //response
        return new RecurringSettingsResponse($payload);
    }

    /**
     * Update recurring settings
     * @param object BolRecurrringSettings instance of the request validation object
     * @param  int  $bol bol id
     * @return \Illuminate\Http\Response
     */
    public function recurringSettingsUpdate(BolRecurrringSettings $request, $id) {

        //get project
        $bols = $this->bolrepo->search($id);
        $bol = $bols->first();

        //update
        $bol->bill_recurring = 'yes';
        $bol->bill_recurring_duration = request('bill_recurring_duration');
        $bol->bill_recurring_period = request('bill_recurring_period');
        $bol->bill_recurring_cycles = request('bill_recurring_cycles');
        $bol->bill_recurring_next = request('bill_recurring_next');

        //refresh bol
        $this->bolrepo->refreshBol($bol);

        //reponse payload
        $payload = [
            'page' => $this->pageSettings('edit'),
            'bols' => $bols,
        ];

        //response
        return new UpdateResponse($payload);
    }

    /**
     * stop an bol from recurring
     * @return \Illuminate\Http\Response
     */
    public function stopRecurring() {

        //get the bol
        $bol = \App\Models\Bol::Where('bill_bolid', request()->route('bol'))->first();

        //update the bol
        $bol->bill_recurring = 'no';
        $bol->bill_recurring_duration = null;
        $bol->bill_recurring_period = null;
        $bol->bill_recurring_cycles = null;
        $bol->bill_recurring_next = null;
        $bol->save();

        //get refreshed bol
        $bols = $this->bolrepo->search(request()->route('bol'));

        //reponse payload
        $payload = [
            'bols' => $bols,
        ];

        //show the form
        return new UpdateResponse($payload);
    }

    /**
     * create line items for ths bol (from submitted expense items)
     * @param int $bill_bolid bol id
     * @return null
     */
    public function expensesPayload($bill_bolid) {

        $bol = \App\Models\Bol::Where('bill_bolid', $bill_bolid)->first();

        //do we have an expense in the payload?
        if (is_array(request('expense_payload'))) {
            foreach (request('expense_payload') as $expense_id) {
                //get the expense
                if ($expense = \App\Models\Expense::Where('expense_id', $expense_id)->first()) {

                    //create a new bol line item
                    $data['lineitem_description'] = $expense->expense_description;
                    $data['lineitem_rate'] = $expense->expense_amount;
                    $data['lineitem_unit'] = __('lang.item');
                    $data['lineitem_quantity'] = 1;
                    $data['lineitem_total'] = $expense->expense_amount;
                    $data['lineitemresource_linked_type'] = 'expense';
                    $data['lineitemresource_linked_type'] = $expense_id;
                    $data['lineitemresource_type'] = 'bol';
                    $data['lineitemresource_id'] = $bill_bolid;
                    $this->lineitemrepo->create($data);

                    //update expense with bol id and mark as bold
                    $expense->expense_billing_status = 'bold';
                    $expense->expense_billable_bolid = $bill_bolid;
                    $expense->save();
                }
            }
        }
    }

    /**
     * create line items for ths bol (from submitted expense items)
     * @param object StripePaymentRepository instance of the repository
     * @param object BolRepository instance of the repository
     * @param int $id client id
     */
    public function paymentStripe(StripePaymentRepository $striperepo, BolRepository $bolrepo, $id) {

        //get bol
        $bols = $bolrepo->search($id);
        $bol = $bols->first();

        //payment payload
        $data = [
            'amount' => $bol->bol_balance,
            'currency' => config('system.settings_stripe_currency'),
            'bol_id' => $bol->bill_bolid,
            'cancel_url' => url('bols/' . $bol->bill_bolid), //in future, this can be bulk payments page
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
     * create line items for ths bol (from submitted expense items)
     * @param object PaypalPaymentRepository instance of the repository
     * @param object BolRepository instance of the repository
     * @param int $id client id
     */
    public function paymentPaypal(PaypalPaymentRepository $paypalrepo, BolRepository $bolrepo, $id) {

        //get bol
        $bols = $bolrepo->search($id);
        $bol = $bols->first();

        //payment payload
        $data = [
            'amount' => $bol->bol_balance,
            'currency' => config('system.settings_paypal_currency'),
            'item_name' => __('lang.bol_payment'),
            'bol_id' => $bol->bill_bolid,
            'ipn_url' => url('/api/paypal/ipn'),
            'cancel_url' => url('bols/' . $bol->bill_bolid), //in future, this can be bulk payments page
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
     * create line items for ths bol (from submitted expense items)
     * @param object PaypalPaymentRepository instance of the repository
     * @param object BolRepository instance of the repository
     * @param int $id client id
     */
    public function paymentRazorpay(RazorpayPaymentRepository $razorpayrepo, BolRepository $bolrepo, $id) {

        //get bol
        $bols = $bolrepo->search($id);
        $bol = $bols->first();

        //payment payload
        $payload = [
            'amount' => $bol->bol_balance,
            'unit_amount' => $bol->bol_balance * 100, //lowest unit (e.g. cents)
            'currency' => config('system.settings_razorpay_currency'),
            'bol_id' => $bol->bill_bolid,
        ];

        //create a new razorpay session
        if (!$order_id = $razorpayrepo->onetimePayment($payload)) {
            abort(409, __('lang.error_request_could_not_be_completed'));
        }

        //more data
        $payload['thank_you_url'] = url('payments/thankyou?gateway=razorpay&order_id=' . $order_id);
        $payload['order_id'] = $order_id;
        $payload['company_name'] = config('system.settings_company_name');
        $payload['description'] = __('lang.bol_payment');
        $payload['image'] = runtimeLogoSmall();
        $payload['thankyou_url'] = url('/payments/thankyou/razorpay');
        $payload['client_name'] = $bol->client_company_name;
        $payload['client_email'] = auth()->user()->email;
        $payload['key'] = config('system.settings_razorpay_keyid');

        //show the view
        return new RazorpayPaymentResponse($payload);
    }

    /**
     * create line items for ths bol (from submitted expense items)
     * @param object PaypalPaymentRepository instance of the repository
     * @param object BolRepository instance of the repository
     * @param int $id client id
     */
    public function paymentMollie(MolliePaymentRepository $mollierepo, BolRepository $bolrepo, $id) {

        //get bol
        $bols = $bolrepo->search($id);
        $bol = $bols->first();

        //payment payload
        $payload = [
            'amount' => $bol->bol_balance,
            'currency' => config('system.settings_mollie_currency'),
            'bol_id' => $bol->bill_bolid,
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
     * basic settings for the bols list page
     * @return array
     */
    private function pageSettings($section = '', $data = array()) {

        //common settings
        $page = [
            'crumbs' => [
                __('lang.sales'),
                __('lang.bols'),
            ],
            'crumbs_special_class' => 'list-pages-crumbs',
            'page' => 'bols',
            'no_results_message' => __('lang.no_results_found'),
            'mainmenu_bols' => 'active',
            'mainmenu_sales' => 'active',
            'submenu_bols' => 'active',
            'sidepanel_id' => 'sidepanel-filter-bols',
            'dynamic_search_url' => url('bols/search?action=search&bolresource_id=' . request('bolresource_id') . '&bolresource_type=' . request('bolresource_type')),
            'add_button_classes' => 'add-edit-bol-button',
            'load_more_button_route' => 'bols',
            'source' => 'list',
        ];
        //default modal settings (modify for sepecif sections)
        $page += [
            'add_modal_title' => __('lang.add_bol'),
            'add_modal_create_url' => url('bols/create?bolresource_id=' . request('bolresource_id') . '&bolresource_type=' . request('bolresource_type')),
            'add_modal_action_url' => url('bols?bolresource_id=' . request('bolresource_id') . '&bolresource_type=' . request('bolresource_type')),
            'add_modal_action_ajax_class' => '',
            'add_modal_action_ajax_loading_target' => 'commonModalBody',
            'add_modal_action_method' => 'POST',
        ];

        //bols list page
        if ($section == 'bols') {
            $page += [
                'meta_title' => __('lang.bols'),
                'heading' => __('lang.bols'),
                'sidepanel_id' => 'sidepanel-filter-bols',
            ];
            if (request('source') == 'ext') {
                $page += [
                    'list_page_actions_size' => 'col-lg-12',
                ];
            }
            return $page;
        }

        //bol page
        if ($section == 'bol') {
            //adjust
            $page['page'] = 'bol';
            //add
            $page += [
                'crumbs' => [
                    __('lang.bol'),
                ],
                'crumbs_special_class' => 'main-pages-crumbs',
                'meta_title' => __('lang.bol') . ' #' . $data->formatted_bill_bolid,
                'heading' => __('lang.project') . ' - ' . $data->project_title,
                'bill_bolid' => request()->segment(2),
                'source_for_filter_panels' => 'ext',
                'section' => 'overview',
            ];

            //crumbs
            $page['crumbs'] = [
                __('lang.sales'),
                __('lang.bols'),
                $data['formatted_bill_bolid'],
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
        $count_all = $this->bolrepo->search('', ['stats' => 'count-all']);
        $count_due = $this->bolrepo->search('', ['stats' => 'count-due']);
        $count_overdue = $this->bolrepo->search('', ['stats' => 'count-overdue']);

        $sum_all = $this->bolrepo->search('', ['stats' => 'sum-all']);
        $sum_payments = $this->bolrepo->search('', ['stats' => 'sum-payments']);
        $sum_due_balances = $this->bolrepo->search('', ['stats' => 'sum-due-balances']);
        $sum_overdue_balances = $this->bolrepo->search('', ['stats' => 'sum-overdue-balances']);

        //default values
        $stats = [
            [
                'value' => runtimeMoneyFormat($sum_all),
                'title' => __('lang.bols') . " ($count_all)",
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