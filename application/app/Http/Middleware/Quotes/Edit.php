<?php

/** --------------------------------------------------------------------------------
 * This middleware class handles [edit] precheck processes for quotes
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Middleware\Quotes;
use Closure;
use Log;

class Edit {

    /**
     * This middleware does the following
     *   2. checks users permissions to [view] quotes
     *   3. modifies the request object as needed
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        //validate module status
        if (!config('visibility.modules.quotes')) {
            abort(404, __('lang.the_requested_service_not_found'));
            return $next($request);
        }

        //quote id
        $bill_quoteid = $request->route('quote');

        //frontend
        $this->fronteEnd();

        //does the quote exist
        if ($bill_quoteid == '' || !$quote = \App\Models\Quote::Where('bill_quoteid', $bill_quoteid)->first()) {
            Log::error("quote could not be found", ['process' => '[permissions][quotes][edit]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'quote id' => $bill_quoteid ?? '']);
            abort(409, __('lang.quote_not_found'));
        }

        //permission: does user have permission edit quotes
        if (auth()->user()->is_team) {
            if (auth()->user()->role->role_quotes >= 2) {

                return $next($request);
            }
        }

        //permission denied
        Log::error("permission denied", ['process' => '[permissions][quotes][edit]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        abort(403);
    }

    /*
     * various frontend and visibility settings
     */
    private function fronteEnd() {

        //vivibility
        config(['visibility.quote_modal_client_project_fields' => false]);

    }
}
