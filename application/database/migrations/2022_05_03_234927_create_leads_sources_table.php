<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadsSourcesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('leads_sources', function(Blueprint $table)
		{
			$table->integer('leadsources_id', true);
			$table->dateTime('leadsources_created');
			$table->dateTime('leadsources_updated');
			$table->integer('leadsources_creatorid');
			$table->string('leadsources_title', 200)->comment('[do not truncate] - good to have example sources like google');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('leads_sources');
	}

}
