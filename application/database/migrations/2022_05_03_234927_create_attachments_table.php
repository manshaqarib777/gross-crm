<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttachmentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('attachments', function(Blueprint $table)
		{
			$table->integer('attachment_id', true);
			$table->string('attachment_uniqiueid', 100);
			$table->dateTime('attachment_created')->nullable();
			$table->dateTime('attachment_updated')->nullable();
			$table->integer('attachment_creatorid');
			$table->integer('attachment_clientid')->nullable()->comment('optional');
			$table->string('attachment_directory', 100);
			$table->string('attachment_filename', 250);
			$table->string('attachment_extension', 20)->nullable();
			$table->string('attachment_type', 20)->nullable()->comment('image | file');
			$table->string('attachment_size', 30)->nullable()->comment('Human readable file size');
			$table->string('attachment_thumbname', 250)->nullable()->comment('optional for images');
			$table->string('attachmentresource_type', 50)->comment('[polymorph] task | expense | ticket | ticketreply');
			$table->integer('attachmentresource_id')->comment('[polymorph] e.g ticket_id');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('attachments');
	}

}
