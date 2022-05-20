<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [publish] process for the bols
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Bols;
use Illuminate\Contracts\Support\Responsable;

class PublishResponse implements Responsable {

    private $payload;

    public function __construct($payload = array()) {
        $this->payload = $payload;
    }

    /**
     * render the view for bols
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request) {

        //set all data to arrays
        foreach ($this->payload as $key => $value) {
            $$key = $value;
        }

        //hide publish button
        $jsondata['dom_visibility'][] = [
            'selector' => '#bol-action-publish-bol',
            'action' => 'hide',
        ];

        //update status (due or overdue)
        $jsondata['dom_visibility'][] = [
            'selector' => '#bol-status-draft',
            'action' => 'hide',
        ];
        if ($bol->bill_status == 'due') {
            $jsondata['dom_visibility'][] = [
                'selector' => '#bol-status-due',
                'action' => 'show',
            ];
        } else {
            $jsondata['dom_visibility'][] = [
                'selector' => '#bol-status-overdue',
                'action' => 'show',
            ];
        }

        //notice
        $jsondata['notification'] = array('type' => 'success', 'value' => __('lang.request_has_been_completed'));

        //response
        return response()->json($jsondata);
    }
}
