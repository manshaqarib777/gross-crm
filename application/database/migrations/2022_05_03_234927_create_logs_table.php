<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('logs', function(Blueprint $table)
		{
			$table->integer('log_id', true);
			$table->string('log_uniqueid', 100)->nullable()->comment('optional');
			$table->dateTime('log_created');
			$table->dateTime('log_updated');
			$table->integer('log_creatorid')->nullable();
			$table->text('log_text')->nullable()->comment('either free text or a (lang) string');
			$table->string('log_text_type', 20)->nullable()->default('text')->comment('text|lang');
			$table->string('log_data_1', 250)->nullable()->comment('optional data');
			$table->string('log_data_2', 250)->nullable()->comment('optional data');
			$table->string('log_data_3', 250)->nullable()->comment('optional data');
			$table->text('log_payload')->nullable()->comment('optional');
			$table->string('logresource_type', 60)->nullable()->comment('debug|subscription|invoice|etc');
			$table->integer('logresource_id')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('logs');
	}

}
