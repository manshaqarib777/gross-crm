<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsAssignedTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('projects_assigned', function(Blueprint $table)
		{
			$table->integer('projectsassigned_id', true)->comment('[truncate]');
			$table->integer('projectsassigned_projectid')->nullable()->index('projectsassigned_projectid');
			$table->integer('projectsassigned_userid')->nullable()->index('projectsassigned_userid');
			$table->dateTime('projectsassigned_created')->nullable();
			$table->dateTime('projectsassigned_updated')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('projects_assigned');
	}

}
