<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksStatusTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tasks_status', function(Blueprint $table)
		{
			$table->integer('taskstatus_id', true);
			$table->dateTime('taskstatus_created')->nullable();
			$table->integer('taskstatus_creatorid')->nullable();
			$table->dateTime('taskstatus_updated')->nullable();
			$table->string('taskstatus_title', 200);
			$table->integer('taskstatus_position');
			$table->string('taskstatus_color', 100)->default('default')->comment('default|primary|success|info|warning|danger|lime|brown');
			$table->string('taskstatus_system_default', 10)->default('no')->comment('yes | no');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('tasks_status');
	}

}
