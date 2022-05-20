<?php

/** --------------------------------------------------------------------------------
 * This middleware class handles [edit] precheck processes for bols
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Middleware\Bols;
use Closure;
use Log;

class Edit {

    /**
     * This middleware does the following
     *   2. checks users permissions to [view] bols
     *   3. modifies the request object as needed
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        //validate module status
        if (!config('visibility.modules.bols')) {
            abort(404, __('lang.the_requested_service_not_found'));
            return $next($request);
        }

        //bol id
        $bill_bolid = $request->route('bol');

        //frontend
        $this->fronteEnd();

        //does the bol exist
        if ($bill_bolid == '' || !$bol = \App\Models\Bol::Where('bill_bolid', $bill_bolid)->first()) {
            Log::error("bol could not be found", ['process' => '[permissions][bols][edit]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'bol id' => $bill_bolid ?? '']);
            abort(409, __('lang.bol_not_found'));
        }

        //permission: does user have permission edit bols
        if (auth()->user()->is_team) {
            if (auth()->user()->role->role_bols >= 2) {

                return $next($request);
            }
        }

        //permission denied
        Log::error("permission denied", ['process' => '[permissions][bols][edit]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        abort(403);
    }

    /*
     * various frontend and visibility settings
     */
    private function fronteEnd() {

        //vivibility
        config(['visibility.bol_modal_client_project_fields' => false]);

    }
}
