<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [thank you] process for the bol-payments
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\BolPayments;
use Illuminate\Contracts\Support\Responsable;

class ThankYouResponse implements Responsable {

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

        return view('pages/bol-payments/thankyou', compact('page'))->render();
    }

}
