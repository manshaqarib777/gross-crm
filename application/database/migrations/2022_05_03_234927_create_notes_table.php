<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('notes', function(Blueprint $table)
		{
			$table->integer('note_id', true);
			$table->dateTime('note_created')->nullable()->comment('always now()');
			$table->dateTime('note_updated')->nullable();
			$table->integer('note_creatorid')->nullable()->index('note_creatorid');
			$table->string('note_title', 250)->nullable();
			$table->text('note_description')->nullable();
			$table->string('note_visibility', 30)->nullable()->default('public')->comment('private|public');
			$table->string('noteresource_type', 50)->nullable()->index('noteresource_type')->comment('[polymorph] client | project | user | lead');
			$table->integer('noteresource_id')->nullable()->index('noteresource_id')->comment('[polymorph] e.g project_id');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('notes');
	}

}
