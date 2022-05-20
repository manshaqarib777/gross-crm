<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [store] process for the quote-payments
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\QuotePayments;
use Illuminate\Contracts\Support\Responsable;

class StoreResponse implements Responsable {

    private $payload;

    public function __construct($payload = array()) {
        $this->payload = $payload;
    }

    /**
     * render the view for quote-payments
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request) {

        //set all data to arrays
        foreach ($this->payload as $key => $value) {
            $$key = $value;
        }

        //prepend content on top of list or show full table
        if ($count == 1) {
            $html = view('pages/quote-payments/components/table/table', compact('quote-payments'))->render();
            $jsondata['dom_html'][] = array(
                'selector' => '#quote-payments-table-wrapper',
                'action' => 'replace',
                'value' => $html);
        } else {
            //prepend content on top of list
            $html = view('pages/quote-payments/components/table/ajax', compact('quote-payments'))->render();
            $jsondata['dom_html'][] = array(
                'selector' => '#quote-payments-td-container',
                'action' => 'prepend',
                'value' => $html);
        }

        //update quote table row
        $html = view('pages/quotes/components/table/ajax', compact('quotes'))->render();
        $jsondata['dom_html'][] = array(
            'selector' => "#quote_" . $quotes->first()->bill_quoteid,
            'action' => 'replace-with',
            'value' => $html);

        //show quote-payment after adding
        if (request('ref') == 'quickadd' && request('show_after_adding') == 'on') {
            $jsondata['redirect_url'] = url("/quote-payments/v/" . $quote-payment->quote-payment_id);
        }

        //close modal
        $jsondata['dom_visibility'][] = array('selector' => '#commonModal', 'action' => 'close-modal');

        //notice
        $jsondata['notification'] = array('type' => 'success', 'value' => __('lang.request_has_been_completed'));

        //response
        return response()->json($jsondata);

    }

}
