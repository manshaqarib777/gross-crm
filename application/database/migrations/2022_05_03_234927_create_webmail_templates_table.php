<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebmailTemplatesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('webmail_templates', function(Blueprint $table)
		{
			$table->integer('webmail_template_id', true);
			$table->dateTime('webmail_template_created');
			$table->dateTime('webmail_template_updated');
			$table->integer('webmail_template_creatorid');
			$table->string('webmail_template_name', 150)->nullable();
			$table->text('webmail_template_body')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('webmail_templates');
	}

}
