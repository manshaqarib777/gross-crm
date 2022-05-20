<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUpdatingTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('updating', function(Blueprint $table)
		{
			$table->integer('updating_id', true);
			$table->dateTime('updating_created');
			$table->dateTime('updating_updated');
			$table->string('updating-name', 100)->nullable()->comment('used for updating the record');
			$table->string('updating_update_version', 10)->nullable()->comment('which version this update is for');
			$table->string('updating_request_path', 250)->nullable()->comment('e.g. /updating/action/update-currency-settings');
			$table->string('updating_update_path', 250)->nullable()->comment('e.g. /updating/action/update-currency-settings');
			$table->string('updating_request_type', 250)->nullable()->default('modal')->comment('modal | url');
			$table->text('updating_notes')->nullable();
			$table->string('updating_completed', 10)->nullable()->default('no');
			$table->dateTime('updating_completed_date')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('updating');
	}

}
