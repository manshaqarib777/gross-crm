<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [update] process for the quotes
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Quotes;
use Illuminate\Contracts\Support\Responsable;

class UpdateResponse implements Responsable {

    private $payload;

    public function __construct($payload = array()) {
        $this->payload = $payload;
    }

    /**
     * render the view for quotes
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
            $html = view('pages/quotes/components/table/ajax', compact('quotes'))->render();
            $jsondata['dom_html'][] = array(
                'selector' => "#quote_" . $quotes->first()->bill_quoteid,
                'action' => 'replace-with',
                'value' => $html);

            //close modal
            $jsondata['dom_visibility'][] = array('selector' => '.modal', 'action' => 'close-modal');

            //notice
            $jsondata['notification'] = array('type' => 'success', 'value' => __('lang.request_has_been_completed'));

        }

        //update initiated on the quote page
        if (request('ref') == 'page') {
            //quote
            $quote = $quotes->first();

            //recurring icon
            $jsondata['dom_visibility'][] = [
                'selector' => '#quote-recurring-icon',
                'action' => ($quote->bill_recurring == 'yes') ? 'show' : 'hide',
            ];

            //recurring icon
            $jsondata['dom_visibility'][] = [
                'selector' => '#quote-action-view-children',
                'action' => ($quote->bill_recurring == 'yes') ? 'show' : 'hide',
            ];
            //recurring icon
            $jsondata['dom_visibility'][] = [
                'selector' => '#quote-action-stop-recurring',
                'action' => ($quote->bill_recurring == 'yes') ? 'show' : 'hide',
            ];

            //reset status
            $jsondata['dom_visibility'][] = [
                'selector' => '.js-quote-statuses',
                'action' => 'hide',
            ];
            $jsondata['dom_visibility'][] = [
                'selector' => '#quote-status-' . $quote->bill_status,
                'action' => 'show',
            ];

            //attach or detattch project
            if (is_numeric($quote->bill_projectid)) {
                $jsondata['dom_html'][] = [
                    'selector' => '#QuoteTitleProject',
                    'action' => 'replace',
                    'value' => __('lang.project') . ' - ' . safestr($quote->project_title),
                ];
                $jsondata['dom_attributes'][] = [
                    'selector' => '#QuoteTitleAttached',
                    'attr' => 'href',
                    'value' =>  url('quotes/'.$quote->bill_projectid),
                ];
                $jsondata['dom_visibility'][] = [
                    'selector' => '#QuoteTitleAttached',
                    'action' => 'show',
                ];
                $jsondata['dom_visibility'][] = [
                    'selector' => '#QuoteTitleNotAttached',
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
                    'selector' => '#QuoteTitleAttached',
                    'action' => 'hide',
                ];
                $jsondata['dom_visibility'][] = [
                    'selector' => '#QuoteTitleNotAttached',
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
