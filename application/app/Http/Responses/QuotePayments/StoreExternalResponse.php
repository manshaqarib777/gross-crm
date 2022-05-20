<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [store] process for the quote-payments
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\QuotePayments;
use Illuminate\Contracts\Support\Responsable;

class StoreExternalResponse implements Responsable {

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

        //request came from quote list page, so replace the row with new
        if (request('source') == 'list' || request('source') == '') {
            $html = view('pages/quotes/components/table/ajax', compact('quotes'))->render();
            $jsondata['dom_html'][] = array(
                'selector' => "#quote_" . $id,
                'action' => 'replace-with',
                'value' => $html);

            //notice
            $jsondata['notification'] = array('type' => 'success', 'value' => __('lang.request_has_been_completed'));
            
        } else {
            //success message
            request()->session()->flash('success-notification', __('lang.request_has_been_completed'));
            //add overlay while page redirects
            $jsondata['dom_classes'][] = [
                'selector' => '#quote-container',
                'action' => 'add',
                'value' => 'overlay',
            ];
            $jsondata['dom_classes'][] = [
                'selector' => '#quote-wrapper',
                'action' => 'add',
                'value' => 'loading',
            ];
            //request came from quote page, reload page
            $jsondata['delayed_redirect_url'] = url("/quotes/$id");
        }

        //close modal
        $jsondata['dom_visibility'][] = array('selector' => '#commonModal', 'action' => 'close-modal');

        //response
        return response()->json($jsondata);

    }

}
