<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('comments', function(Blueprint $table)
		{
			$table->integer('comment_id', true);
			$table->dateTime('comment_created')->nullable();
			$table->dateTime('comment_updated')->nullable();
			$table->integer('comment_creatorid')->index('comment_creatorid');
			$table->integer('comment_clientid')->nullable()->index('comment_clientid')->comment('required for client type resources');
			$table->text('comment_text');
			$table->string('commentresource_type', 50)->index('commentresource_type')->comment('[polymorph] project | ticket | task | lead');
			$table->integer('commentresource_id')->index('commentresource_id')->comment('[polymorph] e.g project_id');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('comments');
	}

}
