<?php

/** --------------------------------------------------------------------------------
 * This middleware class handles [show] precheck processes for quotes
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Middleware\Quotes;
use Closure;
use Log;

class Show {

    /**
     * This middleware does the following
     *   1. validates that the quote exists
     *   2. checks users permissions to [view] the quote
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
            abort(404);
        }

        //team: does user have permission edit quotes
        if (auth()->user()->is_team) {
            if (auth()->user()->role->role_quotes >= 1) {

                return $next($request);
            }
        }

        //client: does user have permission edit quotes
        if (auth()->user()->is_client) {
            //404 for quotes in draft status
            if ($quote->bill_status == 'draft') {
                abort(404);
            }
            //ok
            if ($quote->bill_clientid == auth()->user()->clientid) {
                //only account owner
                if (auth()->user()->account_owner == 'yes') {
                    return $next($request);
                }
            }
        }

        //NB: client db/repository (clientid filter merege) is applied in main controller.php

        //permission denied
        Log::error("permission denied", ['process' => '[permissions][quotes][show]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'quote id' => $bill_quoteid ?? '']);
        abort(403);
    }

    /*
     * various frontend and visibility settings
     */
    private function fronteEnd() {

        //merge data
        request()->merge([
            'resource_query' => 'ref=page',
        ]);

        //default view (not editing)
        config([
            'visibility.bill_mode' => 'viewing',
        ]);

        //are we in editing mode
        if (request()->segment(3) == 'edit-quote') {
            config([
                'visibility.bill_mode' => 'editing',
                'css.bill_mode' => 'editing',
            ]);
        }

        //stripe js
        if (config('system.settings_stripe_status') == 'enabled') {
            config([
                'visibility.stripe_js' => true,
            ]);
        }

        //razorpay js
        if (config('system.settings_razorpay_status') == 'enabled') {
            config([
                'visibility.razorpay_js' => true,
            ]);
        }

        //page level javascript
        config(['js.section' => 'bill']);
    }
}
