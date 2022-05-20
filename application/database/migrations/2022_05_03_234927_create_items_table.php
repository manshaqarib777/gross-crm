<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('items', function(Blueprint $table)
		{
			$table->integer('item_id', true);
			$table->dateTime('item_created')->nullable();
			$table->dateTime('item_updated')->nullable();
			$table->integer('item_categoryid')->default(8)->index('item_categoryid');
			$table->integer('item_creatorid');
			$table->string('item_description', 250)->nullable();
			$table->string('item_unit', 50)->nullable();
			$table->decimal('item_rate', 10);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('items');
	}

}
