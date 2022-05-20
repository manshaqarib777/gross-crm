<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tickets', function(Blueprint $table)
		{
			$table->integer('ticket_id', true);
			$table->dateTime('ticket_created')->nullable();
			$table->dateTime('ticket_updated')->nullable();
			$table->integer('ticket_creatorid')->index('ticket_creatorid');
			$table->integer('ticket_categoryid')->default(9)->index('ticket_categoryid');
			$table->integer('ticket_clientid')->nullable()->index('ticket_clientid');
			$table->integer('ticket_projectid')->nullable()->index('ticket_projectid');
			$table->string('ticket_subject', 250)->nullable();
			$table->text('ticket_message')->nullable();
			$table->string('ticket_priority', 50)->default('normal')->index('ticket_priority')->comment('normal | high | urgent');
			$table->dateTime('ticket_last_updated')->nullable();
			$table->string('ticket_status', 50)->default('open')->index('ticket_status')->comment('open | on_hold | answered | closed');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('tickets');
	}

}
