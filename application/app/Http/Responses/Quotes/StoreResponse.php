<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [store] process for the quotes
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Quotes;
use Illuminate\Contracts\Support\Responsable;

class StoreResponse implements Responsable {

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
        
        //redirect to quote page
        $jsondata['redirect_url'] = url("/quotes/$id/edit-quote");

        //response
        return response()->json($jsondata);
    }

}
