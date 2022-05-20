<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMilestonesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('milestones', function(Blueprint $table)
		{
			$table->integer('milestone_id', true);
			$table->dateTime('milestone_created');
			$table->dateTime('milestone_updated');
			$table->integer('milestone_creatorid')->index('milestone_creatorid');
			$table->string('milestone_title', 250)->default('uncategorised');
			$table->integer('milestone_projectid')->nullable()->index('milestone_projectid');
			$table->integer('milestone_position')->default(1);
			$table->string('milestone_type', 50)->default('categorised')->index('milestone_type')->comment('categorised|uncategorised [1 uncategorised milestone if automatically created when a new project is created]');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('milestones');
	}

}
