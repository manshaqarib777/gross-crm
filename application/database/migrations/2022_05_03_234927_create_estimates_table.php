<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstimatesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('estimates', function(Blueprint $table)
		{
			$table->integer('bill_estimateid', true);
			$table->dateTime('bill_created')->nullable();
			$table->dateTime('bill_updated')->nullable();
			$table->dateTime('bill_date_sent_to_customer')->nullable();
			$table->integer('bill_clientid')->index('bill_clientid');
			$table->integer('bill_projectid')->nullable();
			$table->integer('bill_creatorid')->index('bill_creatorid');
			$table->integer('bill_categoryid')->default(4)->index('bill_categoryid');
			$table->date('bill_date');
			$table->date('bill_expiry_date')->nullable();
			$table->decimal('bill_subtotal', 10)->default(0.00);
			$table->string('bill_discount_type', 30)->nullable()->default('none')->comment('amount | percentage | none');
			$table->decimal('bill_discount_percentage', 10)->nullable()->default(0.00)->comment('actual amount or percentage');
			$table->decimal('bill_discount_amount', 10)->nullable()->default(0.00);
			$table->decimal('bill_amount_before_tax', 10)->nullable()->default(0.00);
			$table->string('bill_tax_type', 20)->nullable()->default('summary')->comment('summary|lineitem|none');
			$table->decimal('bill_tax_total_percentage', 10)->nullable()->default(0.00)->comment('percentage');
			$table->decimal('bill_tax_total_amount', 10)->nullable()->default(0.00)->comment('amount');
			$table->string('bill_adjustment_description', 250)->nullable();
			$table->decimal('bill_adjustment_amount', 10)->nullable()->default(0.00);
			$table->decimal('bill_final_amount', 10)->nullable()->default(0.00);
			$table->text('bill_notes')->nullable();
			$table->text('bill_terms')->nullable();
			$table->string('bill_status', 50)->default('draft')->index('bill_status')->comment('draft | new | accepted | revised | declined | expired');
			$table->string('bill_type', 20)->default('estimate')->index('bill_type')->comment('estimate|invoice');
			$table->string('bill_visibility', 20)->default('visible')->index('bill_visibility')->comment('visible|hidden (used to prevent estimates that are still being cloned from showing in estimates list)');
			$table->string('bill_viewed_by_client', 20)->nullable()->default('no')->comment('yes|no');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('estimates');
	}

}
