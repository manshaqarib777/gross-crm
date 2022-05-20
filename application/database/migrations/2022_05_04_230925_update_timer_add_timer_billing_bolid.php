<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTimerAddTimerBillingBolid extends Migration
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
			$table->integer('timer_billing_bolid')->nullable()->index('timer_billing_bolid')->comment('bol id, if billed');
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
			$table->dropColumn('timer_billing_bolid');
		});	
    }
}
