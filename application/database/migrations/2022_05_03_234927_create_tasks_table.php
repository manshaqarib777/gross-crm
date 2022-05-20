<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tasks', function(Blueprint $table)
		{
			$table->integer('task_id', true);
			$table->string('task_importid', 100)->nullable();
			$table->float('task_position', 10, 0)->comment('increment by 16384');
			$table->dateTime('task_created')->nullable()->comment('always now()');
			$table->dateTime('task_updated')->nullable();
			$table->integer('task_creatorid')->nullable()->index('task_creatorid');
			$table->integer('task_clientid')->nullable()->index('task_clientid')->comment('optional');
			$table->integer('task_projectid')->nullable()->index('taskresource_id')->comment('project_id');
			$table->date('task_date_start')->nullable();
			$table->date('task_date_due')->nullable();
			$table->string('task_title', 250)->nullable();
			$table->text('task_description')->nullable();
			$table->string('task_client_visibility', 100)->nullable()->default('yes');
			$table->integer('task_milestoneid')->nullable()->index('task_milestoneid')->comment('new tasks must be set to the [uncategorised] milestone');
			$table->string('task_previous_status', 100)->nullable()->default('new');
			$table->string('task_priority', 100)->default('normal')->index('task_priority')->comment('normal | high | urgent');
			$table->integer('task_status')->nullable()->default(1);
			$table->string('task_active_state', 100)->nullable()->default('active')->comment('active|archived');
			$table->string('task_billable', 5)->nullable()->default('yes')->index('task_billable')->comment('yes | no');
			$table->string('task_billable_status', 20)->nullable()->default('not_invoiced')->comment('invoiced | not_invoiced');
			$table->integer('task_billable_invoiceid')->nullable()->comment('id of the invoice that it has been billed to');
			$table->integer('task_billable_lineitemid')->nullable()->comment('id of line item that was billed');
			$table->string('task_visibility', 40)->nullable()->default('visible')->index('task_visibility')->comment('visible|hidden (used to prevent tasks that are still being cloned from showing in tasks list)');
			$table->string('task_overdue_notification_sent', 40)->nullable()->default('no')->comment('yes|no');
			$table->string('task_recurring', 40)->nullable()->default('no')->comment('yes|no');
			$table->integer('task_recurring_duration')->nullable()->comment('e.g. 20 (for 20 days)');
			$table->string('task_recurring_period', 30)->nullable()->comment('day | week | month | year');
			$table->integer('task_recurring_cycles')->nullable()->comment('0 for infinity');
			$table->integer('task_recurring_cycles_counter')->nullable()->default(0)->comment('number of times it has been renewed');
			$table->date('task_recurring_last')->nullable()->comment('date when it was last renewed');
			$table->date('task_recurring_next')->nullable()->comment('date when it will next be renewed');
			$table->string('task_recurring_child', 10)->nullable()->default('no')->comment('yes|no');
			$table->dateTime('task_recurring_parent_id')->nullable()->comment('if it was generated from a recurring invoice, the id of parent invoice');
			$table->string('task_recurring_copy_checklists', 10)->nullable()->default('yes')->comment('yes|no');
			$table->string('task_recurring_copy_files', 10)->nullable()->default('yes')->comment('yes|no');
			$table->string('task_recurring_automatically_assign', 10)->nullable()->default('yes')->comment('yes|no');
			$table->string('task_recurring_finished', 10)->nullable()->default('no')->comment('yes|no');
			$table->text('task_custom_field_1')->nullable();
			$table->text('task_custom_field_2')->nullable();
			$table->text('task_custom_field_3')->nullable();
			$table->text('task_custom_field_4')->nullable();
			$table->text('task_custom_field_5')->nullable();
			$table->text('task_custom_field_6')->nullable();
			$table->text('task_custom_field_7')->nullable();
			$table->text('task_custom_field_8')->nullable();
			$table->text('task_custom_field_9')->nullable();
			$table->text('task_custom_field_10')->nullable();
			$table->dateTime('task_custom_field_11')->nullable();
			$table->dateTime('task_custom_field_12')->nullable();
			$table->dateTime('task_custom_field_13')->nullable();
			$table->dateTime('task_custom_field_14')->nullable();
			$table->dateTime('task_custom_field_15')->nullable();
			$table->dateTime('task_custom_field_16')->nullable();
			$table->dateTime('task_custom_field_17')->nullable();
			$table->dateTime('task_custom_field_18')->nullable();
			$table->dateTime('task_custom_field_19')->nullable();
			$table->dateTime('task_custom_field_20')->nullable();
			$table->text('task_custom_field_21')->nullable();
			$table->text('task_custom_field_22')->nullable();
			$table->text('task_custom_field_23')->nullable();
			$table->text('task_custom_field_24')->nullable();
			$table->text('task_custom_field_25')->nullable();
			$table->text('task_custom_field_26')->nullable();
			$table->text('task_custom_field_27')->nullable();
			$table->text('task_custom_field_28')->nullable();
			$table->text('task_custom_field_29')->nullable();
			$table->text('task_custom_field_30')->nullable();
			$table->string('task_custom_field_31', 20)->nullable();
			$table->string('task_custom_field_32', 20)->nullable();
			$table->string('task_custom_field_33', 20)->nullable();
			$table->string('task_custom_field_34', 20)->nullable();
			$table->string('task_custom_field_35', 20)->nullable();
			$table->string('task_custom_field_36', 20)->nullable();
			$table->string('task_custom_field_37', 20)->nullable();
			$table->string('task_custom_field_38', 20)->nullable();
			$table->string('task_custom_field_39', 20)->nullable();
			$table->string('task_custom_field_40', 20)->nullable();
			$table->string('task_custom_field_41', 150)->nullable();
			$table->string('task_custom_field_42', 150)->nullable();
			$table->string('task_custom_field_43', 150)->nullable();
			$table->string('task_custom_field_44', 150)->nullable();
			$table->string('task_custom_field_45', 150)->nullable();
			$table->string('task_custom_field_46', 150)->nullable();
			$table->string('task_custom_field_47', 150)->nullable();
			$table->string('task_custom_field_48', 150)->nullable();
			$table->string('task_custom_field_49', 150)->nullable();
			$table->string('task_custom_field_50', 150)->nullable();
			$table->integer('task_custom_field_51')->nullable();
			$table->integer('task_custom_field_52')->nullable();
			$table->integer('task_custom_field_53')->nullable();
			$table->integer('task_custom_field_54')->nullable();
			$table->integer('task_custom_field_55')->nullable();
			$table->integer('task_custom_field_56')->nullable();
			$table->integer('task_custom_field_57')->nullable();
			$table->integer('task_custom_field_58')->nullable();
			$table->integer('task_custom_field_59')->nullable();
			$table->integer('task_custom_field_60')->nullable();
			$table->decimal('task_custom_field_61', 10)->nullable();
			$table->decimal('task_custom_field_62', 10)->nullable();
			$table->decimal('task_custom_field_63', 10)->nullable();
			$table->decimal('task_custom_field_64', 10)->nullable();
			$table->decimal('task_custom_field_65', 10)->nullable();
			$table->decimal('task_custom_field_66', 10)->nullable();
			$table->decimal('task_custom_field_67', 10)->nullable();
			$table->decimal('task_custom_field_68', 10)->nullable();
			$table->decimal('task_custom_field_69', 10)->nullable();
			$table->decimal('task_custom_field_70', 10)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('tasks');
	}

}
