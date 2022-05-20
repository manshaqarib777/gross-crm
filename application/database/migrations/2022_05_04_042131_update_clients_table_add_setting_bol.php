<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateClientsTableAddSettingBol extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
	{
		Schema::table('clients', function(Blueprint $table)
		{
			$table->string('client_settings_modules_bols', 50)->nullable()->default('enabled')->comment('enabled|disabled');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('clients', function(Blueprint $table)
		{
			$table->dropColumn('client_settings_modules_bols');
		});	
    }
}
