<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [show] process for the bol-payments
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\BolPayments;
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
        $html = view('pages/bol-payments/components/modals/show-bol-payment', compact('bol-payment'))->render();
        $jsondata['dom_html'][] = array(
            'selector' => '#plainModalBody',
            'action' => 'replace',
            'value' => $html);
        $jsondata['dom_html'][] = array(
            'selector' => '#plainModalTitle',
            'action' => 'replace',
            'value' => __('lang.bol-payment'));

        //update browser url
        $jsondata['dom_browser_url'] = [
            'title' => __('lang.bol-payment') . ' - ' . $bol-payment->bol-payment_title,
            'url' => url("/bol-payments/v/" . $bol-payment->bol-payment_id),
        ];

        //ajax response
        return response()->json($jsondata);

    }

}
