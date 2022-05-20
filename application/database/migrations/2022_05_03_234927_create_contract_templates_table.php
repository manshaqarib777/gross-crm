<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractTemplatesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('contract_templates', function(Blueprint $table)
		{
			$table->integer('contracttemplates_id', true)->comment('[do not truncate]');
			$table->dateTime('contracttemplates_created')->nullable();
			$table->dateTime('contracttemplates_updated')->nullable();
			$table->integer('contracttemplates_creatorid')->nullable();
			$table->string('contracttemplates_title', 250)->nullable();
			$table->text('contracttemplates_text')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('contract_templates');
	}

}
