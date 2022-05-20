<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [show] process for the quote-payments
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\QuotePayments;
use Illuminate\Contracts\Support\Responsable;

class ShowResponse implements Responsable {

    private $payload;

    public function __construct($payload = array()) {
        $this->payload = $payload;
    }

    /**
     * render the view
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request) {

        //set all data to arrays
        foreach ($this->payload as $key => $value) {
            $$key = $value;
        }

        //content & title
        $html = view('pages/quote-payments/components/modals/show-quote-payment', compact('quote-payment'))->render();
        $jsondata['dom_html'][] = array(
            'selector' => '#plainModalBody',
            'action' => 'replace',
            'value' => $html);
        $jsondata['dom_html'][] = array(
            'selector' => '#plainModalTitle',
            'action' => 'replace',
            'value' => __('lang.quote-payment'));

        //update browser url
        $jsondata['dom_browser_url'] = [
            'title' => __('lang.quote-payment') . ' - ' . $quote-payment->quote-payment_title,
            'url' => url("/quote-payments/v/" . $quote-payment->quote-payment_id),
        ];

        //ajax response
        return response()->json($jsondata);

    }

}
