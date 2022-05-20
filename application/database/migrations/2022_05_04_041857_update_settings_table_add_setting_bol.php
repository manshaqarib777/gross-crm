<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSettingsTableAddSettingBol extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
	{
		Schema::table('settings', function(Blueprint $table)
		{
			$table->string('settings_modules_bols', 40)->nullable()->default('enabled')->comment('enabled|disabled (bol, payments, products)');
			$table->text('settings_bols_prefix')->nullable();
			$table->smallInteger('settings_bols_recurring_grace_period')->nullable()->comment('Number of days for due date on recurring bols. If set to zero, bols will be given due date same as bol date');
			$table->text('settings_bols_default_terms_conditions')->nullable();
			$table->text('settings_bols_show_view_status');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('settings', function(Blueprint $table)
		{
			$table->dropColumn('settings_modules_bols');
			$table->dropColumn('settings_bols_prefix');
			$table->dropColumn('settings_bols_recurring_grace_period');
			$table->dropColumn('settings_bols_default_terms_conditions');
			$table->dropColumn('settings_bols_show_view_status');
		});	
    }
}
