<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailQueueTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('email_queue', function(Blueprint $table)
		{
			$table->integer('emailqueue_id', true);
			$table->dateTime('emailqueue_created');
			$table->dateTime('emailqueue_updated');
			$table->string('emailqueue_to', 150)->nullable();
			$table->string('emailqueue_from_email', 150)->nullable()->comment('optional (used in sending client direct email)');
			$table->string('emailqueue_from_name', 150)->nullable()->comment('optional (used in sending client direct email)');
			$table->string('emailqueue_subject', 250)->nullable();
			$table->text('emailqueue_message')->nullable();
			$table->string('emailqueue_type', 10)->nullable()->default('general')->index('emailqueue_type')->comment('general|pdf (used for emails that need to generate a pdf)');
			$table->text('emailqueue_attachments')->nullable()->comment('json of request(\'attachments\')');
			$table->string('emailqueue_resourcetype', 10)->nullable()->index('emailqueue_resourcetype')->comment('e.g. invoice. Used mainly for deleting records, when resource has been deleted');
			$table->integer('emailqueue_resourceid')->nullable()->index('emailqueue_resourceid');
			$table->string('emailqueue_pdf_resource_type', 50)->nullable()->index('emailqueue_pdf_resource_type')->comment('estimate|invoice');
			$table->integer('emailqueue_pdf_resource_id')->nullable()->index('emailqueue_pdf_resource_id')->comment('resource id (e.g. estimate id)');
			$table->dateTime('emailqueue_started_at')->nullable()->comment('timestamp of when processing started');
			$table->string('emailqueue_status', 20)->nullable()->default('new')->index('emailqueue_status')->comment('new|processing (set to processing by the cronjob, to avoid duplicate processing)');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('email_queue');
	}

}
