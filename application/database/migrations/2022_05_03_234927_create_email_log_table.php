<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailLogTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('email_log', function(Blueprint $table)
		{
			$table->integer('emaillog_id', true);
			$table->dateTime('emaillog_created')->nullable();
			$table->dateTime('emaillog_updated')->nullable();
			$table->string('emaillog_email', 100)->nullable();
			$table->string('emaillog_subject', 200)->nullable();
			$table->text('emaillog_body')->nullable();
			$table->string('emaillog_attachment', 250)->nullable()->default('attached file name');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('email_log');
	}

}
