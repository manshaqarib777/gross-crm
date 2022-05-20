<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateExpenseAddBolidColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
	{
		Schema::table('expenses', function(Blueprint $table)
		{
			$table->integer('expense_billable_bolid')->nullable()->index('expense_billable_bolid')->comment('id of the bol that it has been billed to');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('expenses', function(Blueprint $table)
		{
			$table->dropColumn('expense_billable_bolid');
		});	
    }
}
