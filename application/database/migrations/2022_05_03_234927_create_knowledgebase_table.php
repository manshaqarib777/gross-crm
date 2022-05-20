<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKnowledgebaseTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('knowledgebase', function(Blueprint $table)
		{
			$table->integer('knowledgebase_id', true);
			$table->dateTime('knowledgebase_created');
			$table->dateTime('knowledgebase_updated');
			$table->integer('knowledgebase_creatorid');
			$table->integer('knowledgebase_categoryid')->index('knowledgebase_categoryid');
			$table->string('knowledgebase_title', 250);
			$table->string('knowledgebase_slug', 250)->nullable();
			$table->text('knowledgebase_text')->nullable();
			$table->string('knowledgebase_embed_video_id', 50)->nullable();
			$table->text('knowledgebase_embed_code')->nullable();
			$table->string('knowledgebase_embed_thumb', 150)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('knowledgebase');
	}

}
