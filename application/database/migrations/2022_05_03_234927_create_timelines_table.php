<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimelinesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('timelines', function(Blueprint $table)
		{
			$table->integer('timeline_id', true);
			$table->integer('timeline_eventid')->index('timeline_eventid');
			$table->string('timeline_resourcetype', 50)->nullable()->index('timeline_resourcetype')->comment('invoices | projects | estimates | etc');
			$table->integer('timeline_resourceid')->nullable()->index('timeline_resourceid')->comment('the id of the item affected');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('timelines');
	}

}
