<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTimerAddTimerBillingQuoteid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
	{
		Schema::table('timers', function(Blueprint $table)
		{
			$table->integer('timer_billing_quoteid')->nullable()->index('timer_billing_quoteid')->comment('quote id, if billed');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('timers', function(Blueprint $table)
		{
			$table->dropColumn('timer_billing_quoteid');
		});	
    }
}
