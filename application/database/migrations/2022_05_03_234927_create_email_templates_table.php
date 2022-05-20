<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailTemplatesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('email_templates', function(Blueprint $table)
		{
			$table->string('emailtemplate_name', 100)->nullable();
			$table->string('emailtemplate_lang', 100)->nullable()->comment('to match to language');
			$table->string('emailtemplate_type', 30)->nullable()->index('emailtemplate_type')->comment('everyone|admin|client');
			$table->string('emailtemplate_category', 30)->nullable()->index('emailtemplate_category')->comment('users|projects|tasks|leads|tickets|billing|estimates|other');
			$table->string('emailtemplate_subject', 250)->nullable();
			$table->text('emailtemplate_body')->nullable();
			$table->text('emailtemplate_variables')->nullable();
			$table->dateTime('emailtemplate_created')->nullable();
			$table->dateTime('emailtemplate_updated')->nullable();
			$table->string('emailtemplate_status', 20)->nullable()->default('enabled')->comment('enabled|disabled');
			$table->string('emailtemplate_language', 50)->nullable();
			$table->string('emailtemplate_real_template', 50)->nullable()->default('yes')->comment('yes|no');
			$table->string('emailtemplate_show_enabled', 50)->nullable()->default('yes')->comment('yes|no');
			$table->integer('emailtemplate_id', true)->comment('x');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('email_templates');
	}

}
