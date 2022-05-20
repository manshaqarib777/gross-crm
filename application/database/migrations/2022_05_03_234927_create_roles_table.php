<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('roles', function(Blueprint $table)
		{
			$table->integer('role_id', true);
			$table->dateTime('role_created')->nullable();
			$table->dateTime('role_updated')->nullable();
			$table->string('role_system', 10)->default('no')->comment('yes | no (system roles cannot be deleted)');
			$table->string('role_type', 10)->index('role_type')->comment('client|team');
			$table->string('role_name', 100);
			$table->boolean('role_clients')->default(0)->comment('none (0) | view (1) | view-add-edit (2) | view-edit-add-delete (3)');
			$table->boolean('role_contacts')->comment('none (0) | view (1) | view-add-edit (2) | view-edit-add-delete (3)');
			$table->boolean('role_invoices')->default(0)->comment('none (0) | view (1) | view-add-edit (2) | view-edit-add-delete (3)');
			$table->boolean('role_estimates')->default(0)->comment('none (0) | view (1) | view-add-edit (2) | view-edit-add-delete (3)');
			$table->boolean('role_payments')->default(0)->comment('none (0) | view (1) | view-add-edit (2) | view-edit-add-delete (3)');
			$table->boolean('role_items')->default(0)->comment('none (0) | view (1) | view-add-edit (2) | view-edit-add-delete (3)');
			$table->boolean('role_tasks')->default(0)->comment('none (0) | view (1) | view-add-edit (2) | view-edit-add-delete (3)');
			$table->string('role_tasks_scope', 20)->default('own')->comment('own | global');
			$table->boolean('role_projects')->default(0)->comment('none (0) | view (1) | view-add-edit (2) | view-edit-add-delete (3)');
			$table->string('role_projects_scope', 20)->default('own')->comment('own | global');
			$table->string('role_projects_billing', 20)->default('0')->comment('none (0) | view (1) | view-add-edit (2)');
			$table->boolean('role_leads')->default(0)->comment('none (0) | view (1) | view-add-edit (2) | view-edit-add-delete (3)');
			$table->string('role_leads_scope', 20)->default('own')->comment('own | global');
			$table->boolean('role_expenses')->default(0)->comment('none (0) | view (1) | view-add-edit (2) | view-edit-add-delete (3)');
			$table->string('role_expenses_scope', 20)->default('own')->comment('own | global');
			$table->integer('role_timesheets')->default(0)->comment('none (0) | view (1) | view-delete (2)');
			$table->string('role_timesheets_scope', 20)->default('own')->comment('own | global');
			$table->boolean('role_team')->default(0)->comment('none (0) | view (1) | view-add-edit (2) | view-edit-add-delete (3)');
			$table->string('role_team_scope', 20)->default('global')->comment('own | global');
			$table->boolean('role_tickets')->default(0)->comment('none (0) | view (1) | view-add-edit (2) | view-edit-add-delete (3)');
			$table->boolean('role_knowledgebase')->default(0)->comment('none (0) | view (1) | view-add-edit (2) | view-edit-add-delete (3)');
			$table->string('role_manage_knowledgebase_categories', 20)->default('no')->comment('yes|no');
			$table->boolean('role_reports')->default(0)->comment('none (0) | view (1) | view-add-edit (2) | view-edit-add-delete (3)');
			$table->string('role_assign_projects', 20)->default('no')->comment('yes|no');
			$table->string('role_assign_leads', 20)->default('no')->comment('yes|no');
			$table->string('role_assign_tasks', 20)->default('no')->comment('yes|no');
			$table->string('role_set_project_permissions', 20)->default('no')->comment('yes|no');
			$table->string('role_subscriptions', 20)->default('0')->comment('none (0) | view (1) | view-add-edit (2) | view-edit-add-delete (3)');
			$table->string('role_templates_projects', 20)->default('1');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('roles');
	}

}
