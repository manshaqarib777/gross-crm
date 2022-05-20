<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsManagerTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('projects_manager', function(Blueprint $table)
		{
			$table->integer('projectsmanager_id', true);
			$table->dateTime('projectsmanager_created');
			$table->dateTime('projectsmanager_updated');
			$table->integer('projectsmanager_projectid')->nullable()->index('projectsmanager_projectid');
			$table->integer('projectsmanager_userid')->index('projectsmanager_userid');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('projects_manager');
	}

}
