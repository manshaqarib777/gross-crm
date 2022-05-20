<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [store] process for the bol-payments
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\BolPayments;
use Illuminate\Contracts\Support\Responsable;

class StoreExternalResponse implements Responsable {

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

        //request came from bol list page, so replace the row with new
        if (request('source') == 'list' || request('source') == '') {
            $html = view('pages/bols/components/table/ajax', compact('bols'))->render();
            $jsondata['dom_html'][] = array(
                'selector' => "#bol_" . $id,
                'action' => 'replace-with',
                'value' => $html);

            //notice
            $jsondata['notification'] = array('type' => 'success', 'value' => __('lang.request_has_been_completed'));
            
        } else {
            //success message
            request()->session()->flash('success-notification', __('lang.request_has_been_completed'));
            //add overlay while page redirects
            $jsondata['dom_classes'][] = [
                'selector' => '#bol-container',
                'action' => 'add',
                'value' => 'overlay',
            ];
            $jsondata['dom_classes'][] = [
                'selector' => '#bol-wrapper',
                'action' => 'add',
                'value' => 'loading',
            ];
            //request came from bol page, reload page
            $jsondata['delayed_redirect_url'] = url("/bols/$id");
        }

        //close modal
        $jsondata['dom_visibility'][] = array('selector' => '#commonModal', 'action' => 'close-modal');

        //response
        return response()->json($jsondata);

    }

}
