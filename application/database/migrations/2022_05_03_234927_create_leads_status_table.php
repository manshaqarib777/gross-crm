<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadsStatusTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('leads_status', function(Blueprint $table)
		{
			$table->integer('leadstatus_id', true);
			$table->dateTime('leadstatus_created')->nullable();
			$table->integer('leadstatus_creatorid')->nullable();
			$table->dateTime('leadstatus_updated')->nullable();
			$table->string('leadstatus_title', 200);
			$table->integer('leadstatus_position');
			$table->string('leadstatus_color', 100)->default('default')->comment('default|primary|success|info|warning|danger|lime|brown');
			$table->string('leadstatus_system_default', 10)->default('no')->comment('yes | no');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('leads_status');
	}

}
