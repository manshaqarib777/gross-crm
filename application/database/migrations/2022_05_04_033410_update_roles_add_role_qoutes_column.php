<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateRolesAddRoleQoutesColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
	{
		Schema::table('roles', function(Blueprint $table)
		{
			$table->boolean('role_quotes')->default(0)->comment('none (0) | view (1) | view-add-edit (2) | view-edit-add-delete (3)');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('roles', function(Blueprint $table)
		{
			$table->dropColumn('role_quotes');
		});	}
}
