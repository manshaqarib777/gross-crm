<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [store] process for the bol-payments
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\BolPayments;
use Illuminate\Contracts\Support\Responsable;

class StoreResponse implements Responsable {

    private $payload;

    public function __construct($payload = array()) {
        $this->payload = $payload;
    }

    /**
     * render the view for bol-payments
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
            $html = view('pages/bol-payments/components/table/table', compact('bol-payments'))->render();
            $jsondata['dom_html'][] = array(
                'selector' => '#bol-payments-table-wrapper',
                'action' => 'replace',
                'value' => $html);
        } else {
            //prepend content on top of list
            $html = view('pages/bol-payments/components/table/ajax', compact('bol-payments'))->render();
            $jsondata['dom_html'][] = array(
                'selector' => '#bol-payments-td-container',
                'action' => 'prepend',
                'value' => $html);
        }

        //update bol table row
        $html = view('pages/bols/components/table/ajax', compact('bols'))->render();
        $jsondata['dom_html'][] = array(
            'selector' => "#bol_" . $bols->first()->bill_bolid,
            'action' => 'replace-with',
            'value' => $html);

        //show bol-payment after adding
        if (request('ref') == 'quickadd' && request('show_after_adding') == 'on') {
            $jsondata['redirect_url'] = url("/bol-payments/v/" . $bol-payment->bol-payment_id);
        }

        //close modal
        $jsondata['dom_visibility'][] = array('selector' => '#commonModal', 'action' => 'close-modal');

        //notice
        $jsondata['notification'] = array('type' => 'success', 'value' => __('lang.request_has_been_completed'));

        //response
        return response()->json($jsondata);

    }

}
