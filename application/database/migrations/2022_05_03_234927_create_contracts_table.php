<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('contracts', function(Blueprint $table)
		{
			$table->integer('contract_id', true);
			$table->dateTime('contract_created')->nullable();
			$table->dateTime('contract_updated')->nullable();
			$table->integer('contract_clientid')->index('contract_clientid');
			$table->integer('contract_projectid')->index('contract_projectid');
			$table->integer('contract_creatorid')->index('contract_creatorid');
			$table->integer('contract_categoryid')->default(6)->index('contract_categoryid');
			$table->string('contract_title', 250);
			$table->dateTime('contract_start_date');
			$table->dateTime('contract_expiry_date');
			$table->decimal('contract_value', 10);
			$table->text('contract_text');
			$table->string('contract_status', 30)->default('draft')->index('contract_status')->comment('draft | pending | active| expired');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('contracts');
	}

}
