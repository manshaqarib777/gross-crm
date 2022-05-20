<?php

/** -------------------------------------------------------------------------------------------------
 * RECURRING INVOICES - DAILY CYCLE
 * Get bols that are due to be renewed today:
 *         - get
 * This cronjob is envoked by by the task scheduler which is in 'application/app/Console/Kernel.php'
 * @package    Grow CRM
 * @author     NextLoop
 *---------------------------------------------------------------------------------------------------*/

namespace App\Cronjobs;
use App\Repositories\RecurringBolRepository;

class RecurringBolsCron {

    public function __invoke(
        RecurringBolRepository $recurringrepo
    ) {

        //[MT] - tenants only
        if (env('MT_TPYE')) {
            if (\Spatie\Multitenancy\Models\Tenant::current() == null) {
                return;
            }
        }

        //log that its run
        //Log::info("Cronjob has started", ['process' => '[RecurringBolsCron]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);

        $recurringrepo->processBols(1);

    }

}