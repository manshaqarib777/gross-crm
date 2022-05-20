<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [elements] process for the bols
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Bols;
use Illuminate\Contracts\Support\Responsable;

class ElementsResponse implements Responsable {

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

        //return rendered txt popover element
        if ($type == 'tax-popover') {
            $view =  view('pages/bill/components/elements/taxpopover', compact('bol', 'taxrates'))->render();
        }

    }

}
