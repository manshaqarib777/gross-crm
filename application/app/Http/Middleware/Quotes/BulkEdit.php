<?php

/** --------------------------------------------------------------------------------
 * This middleware class handles [create] precheck processes for quotes
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Middleware\Quotes;
use Closure;
use Log;

class BulkEdit {

    /**
     * This 'bulk actions' middleware does the following (for edit level actions)
     *   1. If the request was for a sinle item
     *         - single item actions must have a query string '?id=123'
     *         - this id will be merged into the expected 'ids' request array (just as if it was a bulk request)
     *   2. loop through all the 'ids' that are in the post request
     *
     * HTML for the checkbox is expected to be in this format:
     *   <input type="checkbox" name="ids[{{ $quote->bill_quoteid }}]"
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

        //for a single item request - merge into an $ids[x] array and set as if checkox is selected (on)
        if (is_numeric(request('id'))) {
            $ids[request('id')] = 'on';
            request()->merge([
                'ids' => $ids,
            ]);
        }

        //loop through each quote and check permissions
        if (is_array(request('ids'))) {

            //validate the each item in the list exists
            foreach (request('ids') as $id => $value) {
                if (!$quote = \App\Models\Quote::Where('bill_quoteid', $id)->first()) {
                    abort(409, __('lang.one_of_the_selected_items_nolonger_exists'));
                }
            }

            //permission: does user have permission edit quotes
            if (auth()->user()->is_team) {
                if (auth()->user()->role->role_quotes < 2) {
                    abort(403, __('lang.permission_denied_for_this_item') . " - #$id");
                }
            }
            //client - no permissions
            if (auth()->user()->is_client) {
                abort(403);
            }
        } else {
            //no items were passed with this request
            Log::error("no items were sent with this request", ['process' => '[permissions][quotes][change-category]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'quote id' => $bill_quoteid ?? '']);
            abort(409);
        }

        //all is on - passed
        return $next($request);
    }

    /*
     * various frontend and visibility settings
     */
    private function fronteEnd() {

        //vivibility
        config(['visibility.quote_modal_client_project_fields' => false]);

    }
}
