<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxratesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('taxrates', function(Blueprint $table)
		{
			$table->integer('taxrate_id', true);
			$table->string('taxrate_uniqueid', 200)->comment('Used in <js> for identification');
			$table->dateTime('taxrate_created');
			$table->dateTime('taxrate_updated');
			$table->integer('taxrate_creatorid');
			$table->string('taxrate_name', 100);
			$table->decimal('taxrate_value', 10);
			$table->string('taxrate_status', 20)->default('enabled')->comment('enabled|disabled');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('taxrates');
	}

}
