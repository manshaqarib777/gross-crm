<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentSessionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payment_sessions', function(Blueprint $table)
		{
			$table->integer('session_id', true);
			$table->dateTime('session_created')->nullable();
			$table->dateTime('session_updated')->nullable();
			$table->integer('session_creatorid')->nullable()->comment('user making the payment');
			$table->string('session_creator_fullname', 150)->nullable();
			$table->string('session_creator_email', 150)->nullable();
			$table->string('session_gateway_name', 150)->nullable()->index('session_gateway_name')->comment('stripe | paypal | etc');
			$table->string('session_gateway_ref', 150)->nullable()->index('session_gateway_ref')->comment('Stripe - The checkout_session_id | Paypal -');
			$table->decimal('session_amount', 10)->nullable()->comment('amount of the payment');
			$table->string('session_invoices', 250)->nullable()->comment('[currently] - single invoice id | [future] - comma seperated list of invoice id\'s that are for this payment');
			$table->integer('session_subscription')->nullable()->comment('subscription id');
			$table->text('session_payload')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('payment_sessions');
	}

}
