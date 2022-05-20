<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSettingsTableAddSettingQuote extends Migration
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
			$table->string('settings_modules_quotes', 40)->nullable()->default('enabled')->comment('enabled|disabled (quote, payments, products)');
			$table->text('settings_quotes_prefix')->nullable();
			$table->smallInteger('settings_quotes_recurring_grace_period')->nullable()->comment('Number of days for due date on recurring quotes. If set to zero, quotes will be given due date same as quote date');
			$table->text('settings_quotes_default_terms_conditions')->nullable();
			$table->text('settings_quotes_show_view_status');
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
			$table->dropColumn('settings_modules_quotes');
			$table->dropColumn('settings_quotes_prefix');
			$table->dropColumn('settings_quotes_recurring_grace_period');
			$table->dropColumn('settings_quotes_default_terms_conditions');
			$table->dropColumn('settings_quotes_show_view_status');
		});	
    }
}
