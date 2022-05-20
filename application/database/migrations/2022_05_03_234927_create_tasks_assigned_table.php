<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksAssignedTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tasks_assigned', function(Blueprint $table)
		{
			$table->integer('tasksassigned_id', true)->comment('[truncate]');
			$table->integer('tasksassigned_taskid')->index('tasksassigned_taskid');
			$table->integer('tasksassigned_userid')->nullable()->index('tasksassigned_userid');
			$table->dateTime('tasksassigned_created')->nullable();
			$table->dateTime('tasksassigned_updated')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('tasks_assigned');
	}

}
