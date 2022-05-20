<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('projects', function(Blueprint $table)
		{
			$table->integer('project_id', true);
			$table->string('project_type', 30)->default('project')->comment('project|template');
			$table->string('project_importid', 100)->nullable();
			$table->dateTime('project_created')->nullable();
			$table->dateTime('project_updated')->nullable();
			$table->integer('project_clientid')->nullable()->index('FK_projects');
			$table->integer('project_creatorid')->index('project_creatorid')->comment('creator of the project');
			$table->integer('project_categoryid')->nullable()->default(1)->index('project_categoryid')->comment('default category');
			$table->string('project_cover_directory', 100)->nullable();
			$table->string('project_cover_filename', 100)->nullable();
			$table->string('project_title', 250);
			$table->date('project_date_start')->nullable();
			$table->date('project_date_due')->nullable();
			$table->text('project_description')->nullable();
			$table->string('project_status', 50)->nullable()->default('not_started')->index('project_status')->comment('not_started | in_progress | on_hold | cancelled | completed');
			$table->string('project_active_state', 10)->nullable()->default('active')->comment('active|archive');
			$table->boolean('project_progress')->nullable()->default(0);
			$table->decimal('project_billing_rate', 10)->nullable()->default(0.00);
			$table->string('project_billing_type', 40)->nullable()->default('hourly')->comment('hourly | fixed');
			$table->integer('project_billing_estimated_hours')->nullable()->default(0)->comment('estimated hours');
			$table->decimal('project_billing_costs_estimate', 10)->nullable()->default(0.00);
			$table->string('project_progress_manually', 10)->nullable()->default('no')->comment('yes | no');
			$table->string('clientperm_tasks_view', 10)->nullable()->default('yes')->comment('yes | no');
			$table->string('clientperm_tasks_collaborate', 40)->nullable()->default('no')->comment('yes | no');
			$table->string('clientperm_tasks_create', 40)->nullable()->default('yes')->comment('yes | no');
			$table->string('clientperm_timesheets_view', 40)->nullable()->default('yes')->comment('yes | no');
			$table->string('clientperm_expenses_view', 40)->nullable()->default('no')->comment('yes | no');
			$table->string('assignedperm_milestone_manage', 40)->nullable()->default('yes')->comment('yes | no');
			$table->string('assignedperm_tasks_collaborate', 40)->nullable()->comment('yes | no');
			$table->string('project_visibility', 40)->nullable()->default('visible')->index('project_visibility')->comment('visible|hidden (used to prevent projects that are still being cloned from showing in projects list)');
			$table->text('project_custom_field_1')->nullable();
			$table->text('project_custom_field_2')->nullable();
			$table->text('project_custom_field_3')->nullable();
			$table->text('project_custom_field_4')->nullable();
			$table->text('project_custom_field_5')->nullable();
			$table->text('project_custom_field_6')->nullable();
			$table->text('project_custom_field_7')->nullable();
			$table->text('project_custom_field_8')->nullable();
			$table->text('project_custom_field_9')->nullable();
			$table->text('project_custom_field_10')->nullable();
			$table->dateTime('project_custom_field_11')->nullable();
			$table->dateTime('project_custom_field_12')->nullable();
			$table->dateTime('project_custom_field_13')->nullable();
			$table->dateTime('project_custom_field_14')->nullable();
			$table->dateTime('project_custom_field_15')->nullable();
			$table->dateTime('project_custom_field_16')->nullable();
			$table->dateTime('project_custom_field_17')->nullable();
			$table->dateTime('project_custom_field_18')->nullable();
			$table->dateTime('project_custom_field_19')->nullable();
			$table->dateTime('project_custom_field_20')->nullable();
			$table->text('project_custom_field_21')->nullable();
			$table->text('project_custom_field_22')->nullable();
			$table->text('project_custom_field_23')->nullable();
			$table->text('project_custom_field_24')->nullable();
			$table->text('project_custom_field_25')->nullable();
			$table->text('project_custom_field_26')->nullable();
			$table->text('project_custom_field_27')->nullable();
			$table->text('project_custom_field_28')->nullable();
			$table->text('project_custom_field_29')->nullable();
			$table->text('project_custom_field_30')->nullable();
			$table->string('project_custom_field_31', 20)->nullable();
			$table->string('project_custom_field_32', 20)->nullable();
			$table->string('project_custom_field_33', 20)->nullable();
			$table->string('project_custom_field_34', 20)->nullable();
			$table->string('project_custom_field_35', 20)->nullable();
			$table->string('project_custom_field_36', 20)->nullable();
			$table->string('project_custom_field_37', 20)->nullable();
			$table->string('project_custom_field_38', 20)->nullable();
			$table->string('project_custom_field_39', 20)->nullable();
			$table->string('project_custom_field_40', 20)->nullable();
			$table->string('project_custom_field_41', 150)->nullable();
			$table->string('project_custom_field_42', 150)->nullable();
			$table->string('project_custom_field_43', 150)->nullable();
			$table->string('project_custom_field_44', 150)->nullable();
			$table->string('project_custom_field_45', 150)->nullable();
			$table->string('project_custom_field_46', 150)->nullable();
			$table->string('project_custom_field_47', 150)->nullable();
			$table->string('project_custom_field_48', 150)->nullable();
			$table->string('project_custom_field_49', 150)->nullable();
			$table->string('project_custom_field_50', 150)->nullable();
			$table->integer('project_custom_field_51')->nullable();
			$table->integer('project_custom_field_52')->nullable();
			$table->integer('project_custom_field_53')->nullable();
			$table->integer('project_custom_field_54')->nullable();
			$table->integer('project_custom_field_55')->nullable();
			$table->integer('project_custom_field_56')->nullable();
			$table->integer('project_custom_field_57')->nullable();
			$table->integer('project_custom_field_58')->nullable();
			$table->integer('project_custom_field_59')->nullable();
			$table->integer('project_custom_field_60')->nullable();
			$table->decimal('project_custom_field_61', 10)->nullable();
			$table->decimal('project_custom_field_62', 10)->nullable();
			$table->decimal('project_custom_field_63', 10)->nullable();
			$table->decimal('project_custom_field_64', 10)->nullable();
			$table->decimal('project_custom_field_65', 10)->nullable();
			$table->decimal('project_custom_field_66', 10)->nullable();
			$table->decimal('project_custom_field_67', 10)->nullable();
			$table->decimal('project_custom_field_68', 10)->nullable();
			$table->decimal('project_custom_field_69', 10)->nullable();
			$table->decimal('project_custom_field_70', 10)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('projects');
	}

}
