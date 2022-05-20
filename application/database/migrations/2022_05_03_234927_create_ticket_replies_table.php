<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketRepliesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ticket_replies', function(Blueprint $table)
		{
			$table->integer('ticketreply_id', true);
			$table->dateTime('ticketreply_created');
			$table->dateTime('ticketreply_updated');
			$table->integer('ticketreply_creatorid')->index('ticketreply_creatorid');
			$table->integer('ticketreply_clientid')->nullable()->index('ticketreply_clientid');
			$table->integer('ticketreply_ticketid')->index('ticketreply_ticketid');
			$table->text('ticketreply_text');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('ticket_replies');
	}

}
