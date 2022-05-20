<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('timers', function(Blueprint $table)
		{
			$table->integer('timer_id', true);
			$table->dateTime('timer_created')->nullable();
			$table->dateTime('timer_updated')->nullable();
			$table->integer('timer_creatorid')->nullable()->index('timer_creatorid');
			$table->integer('timer_started')->nullable()->comment('unix time stam for when the timer was started');
			$table->integer('timer_stopped')->nullable()->default(0)->comment('unix timestamp for when the timer was stopped');
			$table->integer('timer_time')->nullable()->default(0)->comment('seconds');
			$table->integer('timer_taskid')->nullable()->index('timer_taskid');
			$table->integer('timer_projectid')->nullable()->default(0)->index('timer_projectid')->comment('needed for repository filtering');
			$table->integer('timer_clientid')->nullable()->default(0)->index('timer_clientid')->comment('needed for repository filtering');
			$table->string('timer_status', 20)->nullable()->default('running')->index('timer_status')->comment('running | stopped');
			$table->string('timer_billing_status', 50)->nullable()->default('not_invoiced')->index('timer_billing_status')->comment('invoiced | not_invoiced');
			$table->integer('timer_billing_invoiceid')->nullable()->index('timer_billing_invoiceid')->comment('invoice id, if billed');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('timers');
	}

}
