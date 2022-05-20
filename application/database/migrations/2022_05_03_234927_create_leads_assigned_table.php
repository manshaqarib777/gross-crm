<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadsAssignedTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('leads_assigned', function(Blueprint $table)
		{
			$table->integer('leadsassigned_id', true);
			$table->integer('leadsassigned_leadid')->nullable();
			$table->integer('leadsassigned_userid')->nullable()->index('leadsassigned_userid');
			$table->dateTime('leadsassigned_created');
			$table->dateTime('leadsassigned_updated');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('leads_assigned');
	}

}
