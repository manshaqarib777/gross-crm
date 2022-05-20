<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChecklistsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('checklists', function(Blueprint $table)
		{
			$table->integer('checklist_id', true);
			$table->integer('checklist_position');
			$table->dateTime('checklist_created');
			$table->dateTime('checklist_updated');
			$table->integer('checklist_creatorid');
			$table->integer('checklist_clientid')->nullable();
			$table->text('checklist_text');
			$table->string('checklist_status', 250)->default('pending')->comment('pending | completed');
			$table->string('checklistresource_type', 50)->index('checklistresource_type');
			$table->integer('checklistresource_id')->index('checklistresource_id');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('checklists');
	}

}
