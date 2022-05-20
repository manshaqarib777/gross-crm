<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKbCategoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('kb_categories', function(Blueprint $table)
		{
			$table->integer('kbcategory_id', true);
			$table->dateTime('kbcategory_created');
			$table->dateTime('kbcategory_updated');
			$table->integer('kbcategory_creatorid');
			$table->string('kbcategory_title', 250);
			$table->text('kbcategory_description')->nullable();
			$table->integer('kbcategory_position')->nullable();
			$table->string('kbcategory_visibility', 50)->nullable()->default('everyone')->comment('everyone | team | client');
			$table->string('kbcategory_slug', 250)->nullable();
			$table->string('kbcategory_icon', 250)->nullable();
			$table->string('kbcategory_type', 50)->nullable()->default('text')->comment('text|video');
			$table->string('kbcategory_system_default', 250)->nullable()->default('no')->comment('yes | no');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('kb_categories');
	}

}
