<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('category_users', function(Blueprint $table)
		{
			$table->integer('categoryuser_id', true);
			$table->integer('categoryuser_categoryid');
			$table->integer('categoryuser_userid');
			$table->dateTime('categoryuser_created');
			$table->dateTime('categoryuser_updated');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('category_users');
	}

}
