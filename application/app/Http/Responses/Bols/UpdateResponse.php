<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [update] process for the bols
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Bols;
use Illuminate\Contracts\Support\Responsable;

class UpdateResponse implements Responsable {

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

        //update initiated on a list page
        if (request('ref') == 'list') {
            //replace the row of this record
            $html = view('pages/bols/components/table/ajax', compact('bols'))->render();
            $jsondata['dom_html'][] = array(
                'selector' => "#bol_" . $bols->first()->bill_bolid,
                'action' => 'replace-with',
                'value' => $html);

            //close modal
            $jsondata['dom_visibility'][] = array('selector' => '.modal', 'action' => 'close-modal');

            //notice
            $jsondata['notification'] = array('type' => 'success', 'value' => __('lang.request_has_been_completed'));

        }

        //update initiated on the bol page
        if (request('ref') == 'page') {
            //bol
            $bol = $bols->first();

            //recurring icon
            $jsondata['dom_visibility'][] = [
                'selector' => '#bol-recurring-icon',
                'action' => ($bol->bill_recurring == 'yes') ? 'show' : 'hide',
            ];

            //recurring icon
            $jsondata['dom_visibility'][] = [
                'selector' => '#bol-action-view-children',
                'action' => ($bol->bill_recurring == 'yes') ? 'show' : 'hide',
            ];
            //recurring icon
            $jsondata['dom_visibility'][] = [
                'selector' => '#bol-action-stop-recurring',
                'action' => ($bol->bill_recurring == 'yes') ? 'show' : 'hide',
            ];

            //reset status
            $jsondata['dom_visibility'][] = [
                'selector' => '.js-bol-statuses',
                'action' => 'hide',
            ];
            $jsondata['dom_visibility'][] = [
                'selector' => '#bol-status-' . $bol->bill_status,
                'action' => 'show',
            ];

            //attach or detattch project
            if (is_numeric($bol->bill_projectid)) {
                $jsondata['dom_html'][] = [
                    'selector' => '#BolTitleProject',
                    'action' => 'replace',
                    'value' => __('lang.project') . ' - ' . safestr($bol->project_title),
                ];
                $jsondata['dom_attributes'][] = [
                    'selector' => '#BolTitleAttached',
                    'attr' => 'href',
                    'value' =>  url('bols/'.$bol->bill_projectid),
                ];
                $jsondata['dom_visibility'][] = [
                    'selector' => '#BolTitleAttached',
                    'action' => 'show',
                ];
                $jsondata['dom_visibility'][] = [
                    'selector' => '#BolTitleNotAttached',
                    'action' => 'hide',
                ];
                $jsondata['dom_visibility'][] = [
                    'selector' => '#bill-actions-attach-project',
                    'action' => 'hide',
                ];
                $jsondata['dom_visibility'][] = [
                    'selector' => '#bill-actions-dettach-project',
                    'action' => 'show',
                ];
            } else {
                $jsondata['dom_visibility'][] = [
                    'selector' => '#BolTitleAttached',
                    'action' => 'hide',
                ];
                $jsondata['dom_visibility'][] = [
                    'selector' => '#BolTitleNotAttached',
                    'action' => 'show',
                ];
                $jsondata['dom_visibility'][] = [
                    'selector' => '#bill-actions-attach-project',
                    'action' => 'show',
                ];
                $jsondata['dom_visibility'][] = [
                    'selector' => '#bill-actions-dettach-project',
                    'action' => 'hide',
                ];
            }

            //close modal
            $jsondata['dom_visibility'][] = array('selector' => '.modal', 'action' => 'close-modal');
        }

        //response
        return response()->json($jsondata);

    }

}
