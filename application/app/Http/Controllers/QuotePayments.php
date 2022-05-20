<?php

/** --------------------------------------------------------------------------------
 * This controller manages all the business logic for payments
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\QuotePayments\QuotePaymentStoreUpdate;
use App\Http\Responses\QuotePayments\CreateResponse;
use App\Http\Responses\QuotePayments\CreateEmailResponse;
use App\Http\Responses\QuotePayments\DestroyResponse;
use App\Http\Responses\QuotePayments\IndexResponse;
use App\Http\Responses\QuotePayments\ShowResponse;
use App\Http\Responses\QuotePayments\StoreExternalResponse;
use App\Http\Responses\QuotePayments\StoreResponse;
use App\Http\Responses\QuotePayments\ThankYouResponse;
use App\Repositories\QuoteRepository;
use App\Repositories\QuotePaymentRepository;
use App\Repositories\ProjectRepository;
use App\Repositories\RazorpayPaymentRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use App\Http\Responses\QuotePayments\UpdateResponse;

class QuotePayments extends Controller {

    /**
     * The payment repository instance.
     */
    protected $paymentrepo;

    /**
     * The user repository instance.
     */
    protected $userrepo;

    /**
     * The quote repository instance.
     */
    protected $quoterepo;

    //contruct
    public function __construct(
        QuotePaymentRepository $paymentrepo,
        UserRepository $userrepo,
        QuoteRepository $quoterepo) {

        //parent
        parent::__construct();

        //authenticated
        $this->middleware('auth');

        $this->middleware('paymentsMiddlewareIndex')->only(
            [
                'index',
                'update',
                'store',
            ]);

        $this->middleware('paymentsMiddlewareShow')->only([
            'show',
        ]);

        $this->middleware('paymentsMiddlewareDestroy')->only([
            'destroy',
        ]);

        $this->middleware('paymentsMiddlewareCreate')->only([
            'create',
            'store',
        ]);

        $this->middleware('paymentsMiddlewareEdit')->only([
            'edit',
            'update',
        ]);

        $this->middleware('paymentsMiddlewareBulkEdit')->only([
            'changeCategoryUpdate',
        ]);


        //for rendering quotes table, after payment
        $this->middleware('quotesMiddlewareIndex')->only([
            'store',
        ]);

        //when adding from quote list page
        $this->middleware('quotesMiddlewareIndex')->only(['store']);

        $this->paymentrepo = $paymentrepo;

        $this->userrepo = $userrepo;

        $this->quoterepo = $quoterepo;

    }

    /**
     * Display a listing of payments
     * @param object ProjectRepository instance of the repository
     * @return \Illuminate\Http\Response
     */
    public function index(ProjectRepository $projectrepo) {

        $projects = [];

        //get payments
        $payments = $this->paymentrepo->search();

        //get clients project list
        if (config('visibility.filter_panel_clients_projects')) {
            if (is_numeric(request('paymentresource_id'))) {
                $projects = $projectrepo->search('', ['project_clientid' => request('paymentresource_id')]);
            }
        }

        //reponse payload
        $payload = [
            'page' => $this->pageSettings('payments'),
            'payments' => $payments,
            'projects' => $projects,
            'stats' => $this->statsWidget(),
        ];

        //show the view
        return new IndexResponse($payload);
    }

    /**
     * Show the form for creating a new payment
     * @return \Illuminate\Http\Response
     */
    public function create() {

        $already_paid = false;

        //get the quote
        if (request()->filled('bill_quoteid')) {
            $quotes = $this->quoterepo->search(request('bill_quoteid'));
            $quote = $quotes->first();
        } else {
            $quote = [];
        }

        //reponse payload
        $payload = [
            'page' => $this->pageSettings('create'),
            'quote' => $quote,
        ];

        //show the form
        return new CreateResponse($payload);
    }


    /**
     * Show the form for creating a new payment
     * @return \Illuminate\Http\Response
     */
    public function email() {

        $already_paid = false;

        //get the quote
        if (request()->filled('bill_quoteid')) {
            $quotes = $this->quoterepo->search(request('bill_quoteid'));
            $quote = $quotes->first();
        } else {
            $quote = [];
        }

        //reponse payload
        $payload = [
            'page' => $this->pageSettings('create'),
            'quote' => $quote,
        ];

        //show the form
        return new CreateEmailResponse($payload);
    }

    /**
     * Store a newly created payment in storage.
     * @param object QuotePaymentStoreUpdate instance of the request validation object
     * @param object QuoteRepository instance of the repository
     * @return \Illuminate\Http\Response
     */
    public function store(QuotePaymentStoreUpdate $request, QuoteRepository $quoterepo) {

        //not found
        $quote = $this->quoterepo->search(request('payment_quoteid'));
        if (!$quote = $quote->first()) {
            abort(409, __('lang.error_loading_item'));
        }

        //additional information
        request()->merge([
            'payment_clientid' => $quote->bill_clientid,
            'payment_projectid' => $quote->bill_projectid,
            'payment_creatorid' => auth()->id(),
        ]);

        //create the project
        if (!$payment_id = $this->paymentrepo->create()) {
            abort(409);
        }

        //get the project object (friendly for rendering in blade template)
        $payments = $this->paymentrepo->search($payment_id);
        $payment = $payments->first();

        //counting rows
        $rows = $this->paymentrepo->search();
        $count = $rows->total();

        //refresh the quote
        $quoterepo->refreshQuote(request('payment_quoteid'));

        //get refreshed quote
        $quotes = $quoterepo->search(request('payment_quoteid'));
        $quote = $quotes->first();

        //reponse payload
        $payload = [
            'payments' => $payments,
            'payment' => $payment,
            'count' => $count,
            'quotes' => $quotes,
            'id' => request('payment_quoteid'),
        ];

        /** --------------------------------------------------------------------------
         * send email [client] [queued]
         * - thank you email to user who placed order
         * --------------------------------------------------------------------------*/
        if (request('send_payment_email') == 'on') {
            if ($user = $this->userrepo->getClientAccountOwner($quote->bill_clientid)) {
                $data = [
                    'payment_transaction_id' => ($payment->payment_transaction_id != '') ? $payment->payment_transaction_id : '---',
                    'payment_amount' => runtimeMoneyFormat($payment->payment_amount),
                    'paid_by_name' => $user->first_name . ' ' . $user->last_name,
                    'payment_gateway' => $payment->payment_gateway,
                ];
                $mail = new \App\Mail\QuotePaymentReceived($user, $data, $quote);
                $mail->build();
            }
        }

        //payment being added from quote pages
        if (request()->get('ref') == 'quote') {
            return new StoreExternalResponse($payload);
        }

        //process reponse
        return new StoreResponse($payload);

    }

    /**
     * display a note via ajax modal
     * @param int $id payment id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {

        //get the note
        $payment = $this->paymentrepo->search($id);

        //note not found
        if (!$payment = $payment->first()) {
            abort(409, __('lang.payment_not_found'));
        }

        //reponse payload
        $payload = [
            'payment' => $payment,
        ];

        //process reponse
        return new ShowResponse($payload);
    }

        /**
     * show the form to edit a resource
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {

        //get the payment
        if (!$payment = \App\Models\QuotePayment::Where('payment_id', $id)->first()) {
            abort(404);
        }

        //page
        $html = view('pages/payments/components/modals/edit-payment', compact('payment'))->render();
        $jsondata['dom_html'][] = [
            'selector' => '#commonModalBody',
            'action' => 'replace',
            'value' => $html,
        ];

        //postrun
        $jsondata['postrun_functions'][] = [
            'value' => 'NXPayementCreate',
        ];

        //render
        return response()->json($jsondata);

    }

    /**
     * show the form to create a new resource
     *
     * @return \Illuminate\Http\Response
     */
    public function update(QuotePaymentStoreUpdate $request, $id) {

        //get the item
        $payment = \App\Models\QuotePayment::Where('payment_id', $id)->first();

        //update record
        $payment->payment_amount = request('payment_amount');
        $payment->payment_date = request('payment_date');
        $payment->payment_gateway = request('payment_gateway');
        $payment->payment_transaction_id = request('payment_transaction_id');
        $payment->payment_notes = request('payment_notes');
        $payment->save();

        //get friendly row
        $payments = $this->paymentrepo->search($id);

        //refresh quote
        $this->quoterepo->refreshQuote($payment->payment_quoteid);

        //payload
        $payload = [
            'payments' => $payments,
            'payment' => $payments->first(),
            'page' => $this->pageSettings(),
        ];

        //return view
        return new UpdateResponse($payload);

    }

    /**
     * Remove the specified payment from storage.
     * @return \Illuminate\Http\Response
     */
    public function destroy() {

        //delete each record in the array
        $allrows = array();
        foreach (request('ids') as $id => $value) {
            //only checked items
            if ($value == 'on') {
                //get the payment
                $payment = \App\Models\QuotePayment::Where('payment_id', $id)->first();
                //delete client
                $payment->delete();
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
     * display a thank you for your payment
     * @return \Illuminate\Http\Response
     */
    public function thankYou() {

        //team member redirect to home
        if (auth()->user()->type == 'team') {
            return redirect('/home');
        }

        //reponse payload
        $payload = [
            'page' => $this->pageSettings('payments'),
        ];

        //process reponse
        return new ThankYouResponse($payload);
    }

    /**
     * display a thank you for your payment
     * @return \Illuminate\Http\Response
     */
    public function thankYouRazorpay(RazorpayPaymentRepository $razorpayrepo) {

        //team member redirect to home
        if (auth()->user()->type == 'team') {
            return redirect('/home');
        }

        //validate
        if (!$razorpayrepo->verifyTransaction()) {
            abort(409, __('lang.error_request_could_not_be_completed') . '. ' . __('lang.please_contact_support'));
        }

        //register the payment (webhook) for processing by cronjob
        $razorpayrepo->registerQuotePayment();

        //reponse payload
        $payload = [
            'page' => $this->pageSettings('payments'),
        ];

        //process reponse
        return new ThankYouResponse($payload);
    }

    /**
     * basic page setting for this section of the app
     * @param string $section page section (optional)
     * @param array $data any other data (optional)
     * @return array
     */
    private function pageSettings($section = '', $data = []) {

        //common settings
        $page = [
            'crumbs' => [
                __('lang.billing'),
                __('lang.payments'),
            ],
            'crumbs_special_class' => 'list-pages-crumbs',
            'page' => 'payments',
            'mainmenu_payments' => 'active',
            'mainmenu_sales' => 'active',
            'submenu_payments' => 'active',
            'no_results_message' => __('lang.no_results_found'),
            'sidepanel_id' => 'sidepanel-filter-payments',
            'dynamic_search_url' => url('payments/search?action=search&paymentresource_id=' . request('paymentresource_id') . '&paymentresource_type=' . request('paymentresource_type')),
            'load_more_button_route' => 'payments',
            'source' => 'list',
        ];

        //default modal settings (modify for sepecif sections)
        $page += [
            'add_modal_title' => __('lang.add_new_payment'),
            'add_modal_create_url' => url('payments/create?paymentresource_id=' . request('paymentresource_id') . '&paymentresource_type=' . request('paymentresource_type')),
            'add_modal_action_url' => url('payments?paymentresource_id=' . request('paymentresource_id') . '&paymentresource_type=' . request('paymentresource_type')),
            'add_modal_action_ajax_class' => '',
            'add_modal_action_ajax_loading_target' => 'commonModalBody',
            'add_modal_action_method' => 'POST',
        ];

        //payments list page
        if ($section == 'payments') {
            $page += [
                'meta_title' => __('lang.payments'),
                'heading' => __('lang.payments'),
                'mainmenu_billing' => 'active',
                'mainmenu_payments' => 'active',
            ];
            if (request('source') == 'ext') {
                $page += [
                    'list_page_actions_size' => 'col-lg-12',
                ];
            }
            return $page;
        }

        //ext page settings
        if ($section == 'ext') {

            $page += [
                'list_page_actions_size' => 'col-lg-12',

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

        //get expense (all rows - for stats etc)
        $count_all = $this->paymentrepo->search('', ['stats' => 'count-all']);
        $sum_all = $this->paymentrepo->search('', ['stats' => 'sum-all']);
        $sum_today = $this->paymentrepo->search('', ['stats' => 'sum-today']);
        $sum_this_month = $this->paymentrepo->search('', ['stats' => 'sum-this-month']);
        $sum_this_year = $this->paymentrepo->search('', ['stats' => 'sum-this-year']);

        //default values
        $stats = [
            [
                'value' => runtimeMoneyFormat($sum_all),
                'title' => __('lang.all') . " ($count_all)",
                'percentage' => '100%',
                'color' => 'bg-info',
            ],
            [
                'value' => runtimeMoneyFormat($sum_today),
                'title' => __('lang.today'),
                'percentage' => '100%',
                'color' => 'bg-primary',
            ],
            [
                'value' => runtimeMoneyFormat($sum_this_month),
                'title' => __('lang.this_month'),
                'percentage' => '100%',
                'color' => 'bg-success',
            ],
            [
                'value' => runtimeMoneyFormat($sum_this_year),
                'title' => __('lang.this_year'),
                'percentage' => '100%',
                'color' => 'bg-default',
            ],
        ];

        //return
        return $stats;
    }
}
