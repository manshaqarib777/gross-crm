<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('subscriptions', function(Blueprint $table)
		{
			$table->integer('subscription_id', true);
			$table->string('subscription_gateway_id', 250)->nullable()->index('subscription_gateway_id');
			$table->dateTime('subscription_created')->nullable();
			$table->dateTime('subscription_updated')->nullable();
			$table->integer('subscription_creatorid')->index('subscription_creatorid');
			$table->integer('subscription_clientid')->index('subscription_clientid');
			$table->integer('subscription_categoryid')->default(4)->index('subscription_categoryid');
			$table->integer('subscription_projectid')->nullable()->index('subscription_projectid')->comment('optional');
			$table->string('subscription_gateway_product', 250)->nullable()->index('subscription_gateway_product')->comment('stripe product id');
			$table->string('subscription_gateway_price', 250)->nullable()->index('subscription_gateway_price')->comment('stripe price id');
			$table->string('subscription_gateway_product_name', 250)->nullable()->comment('e.g. Glod Plan');
			$table->integer('subscription_gateway_interval')->nullable()->comment('e.g. 2');
			$table->string('subscription_gateway_period', 50)->nullable()->comment('e.g. months');
			$table->dateTime('subscription_date_started')->nullable();
			$table->dateTime('subscription_date_ended')->nullable();
			$table->date('subscription_date_renewed')->nullable()->comment('from stripe');
			$table->date('subscription_date_next_renewal')->nullable()->comment('from stripe');
			$table->text('subscription_gateway_last_message')->nullable()->comment('from stripe');
			$table->dateTime('subscription_gateway_last_message_date')->nullable();
			$table->decimal('subscription_subtotal', 10)->default(0.00);
			$table->decimal('subscription_amount_before_tax', 10)->nullable()->default(0.00);
			$table->decimal('subscription_tax_percentage', 10)->nullable()->default(0.00)->comment('percentage');
			$table->decimal('subscription_tax_amount', 10)->nullable()->default(0.00)->comment('amount');
			$table->decimal('subscription_final_amount', 10)->nullable()->default(0.00);
			$table->text('subscription_notes')->nullable();
			$table->string('subscription_status', 50)->nullable()->default('pending')->index('subscription_status')->comment('pending | active | failed | paused | cancelled');
			$table->string('subscription_visibility', 50)->nullable()->default('visible')->index('subscription_visibility')->comment('visible | invisible');
			$table->string('subscription_cron_status', 20)->nullable()->default('none')->comment('none|processing|completed|error  (used to prevent collisions when recurring invoiced)');
			$table->dateTime('subscription_cron_date')->nullable()->comment('date when cron was run');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('subscriptions');
	}

}
