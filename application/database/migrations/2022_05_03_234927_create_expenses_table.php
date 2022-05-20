<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpensesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('expenses', function(Blueprint $table)
		{
			$table->integer('expense_id', true);
			$table->string('expense_importid', 100)->nullable();
			$table->date('expense_created')->nullable();
			$table->date('expense_updated')->nullable();
			$table->date('expense_date')->nullable();
			$table->integer('expense_clientid')->nullable()->index('expense_clientid');
			$table->integer('expense_projectid')->nullable()->index('expense_projectid');
			$table->integer('expense_creatorid')->index('expense_creatorid');
			$table->integer('expense_categoryid')->default(7);
			$table->decimal('expense_amount', 10);
			$table->text('expense_description')->nullable();
			$table->text('expense_type')->nullable()->comment('business|client');
			$table->string('expense_billable', 30)->nullable()->default('not_billable')->index('expense_billable')->comment('billable | not_billable');
			$table->string('expense_billing_status', 30)->nullable()->default('not_invoiced')->index('expense_billing_status')->comment('invoiced | not_invoiced');
			$table->integer('expense_billable_invoiceid')->nullable()->index('expense_billable_invoiceid')->comment('id of the invoice that it has been billed to');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('expenses');
	}

}
