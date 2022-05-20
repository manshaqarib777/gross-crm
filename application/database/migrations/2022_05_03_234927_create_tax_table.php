<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tax', function(Blueprint $table)
		{
			$table->integer('tax_id', true);
			$table->integer('tax_taxrateid')->comment('Reference to tax rates table');
			$table->dateTime('tax_created');
			$table->dateTime('tax_updated');
			$table->string('tax_name', 100)->nullable();
			$table->decimal('tax_rate', 10)->nullable();
			$table->string('taxresource_type', 50)->nullable()->index('taxresource_type')->comment('invoice|estimate|lineitem');
			$table->integer('taxresource_id')->nullable()->index('taxresource_id');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('tax');
	}

}
