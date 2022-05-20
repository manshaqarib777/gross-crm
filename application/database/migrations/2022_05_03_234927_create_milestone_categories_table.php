<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMilestoneCategoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('milestone_categories', function(Blueprint $table)
		{
			$table->integer('milestonecategory_id', true);
			$table->dateTime('milestonecategory_created');
			$table->dateTime('milestonecategory_updated');
			$table->integer('milestonecategory_creatorid');
			$table->string('milestonecategory_title', 250);
			$table->integer('milestonecategory_position');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('milestone_categories');
	}

}
