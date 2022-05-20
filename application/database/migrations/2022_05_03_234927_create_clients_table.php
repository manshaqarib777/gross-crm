<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('clients', function(Blueprint $table)
		{
			$table->integer('client_id', true);
			$table->string('client_importid', 100)->nullable();
			$table->dateTime('client_created')->nullable();
			$table->dateTime('client_updated')->nullable();
			$table->integer('client_creatorid')->index('client_creatorid');
			$table->integer('client_created_from_leadid')->comment('the lead that the customer was created from');
			$table->integer('client_categoryid')->nullable()->default(2)->index('client_categoryid')->comment('default category');
			$table->string('client_company_name', 150);
			$table->text('client_description')->nullable();
			$table->string('client_phone', 50)->nullable();
			$table->string('client_logo_folder', 50)->nullable();
			$table->string('client_logo_filename', 50)->nullable();
			$table->string('client_website', 250)->nullable();
			$table->string('client_vat', 50)->nullable();
			$table->string('client_billing_street', 200)->nullable();
			$table->string('client_billing_city', 100)->nullable();
			$table->string('client_billing_state', 100)->nullable();
			$table->string('client_billing_zip', 50)->nullable();
			$table->string('client_billing_country', 100)->nullable();
			$table->string('client_shipping_street', 250)->nullable();
			$table->string('client_shipping_city', 100)->nullable();
			$table->string('client_shipping_state', 100)->nullable();
			$table->string('client_shipping_zip', 50)->nullable();
			$table->string('client_shipping_country', 100)->nullable();
			$table->string('client_status', 50)->default('active')->index('client_status')->comment('active|suspended');
			$table->string('client_app_modules', 50)->nullable()->default('system')->comment('system|custom');
			$table->string('client_settings_modules_projects', 50)->nullable()->default('enabled')->comment('enabled|disabled');
			$table->string('client_settings_modules_invoices', 50)->nullable()->default('enabled')->comment('enabled|disabled');
			$table->string('client_settings_modules_payments', 50)->nullable()->default('enabled')->comment('enabled|disabled');
			$table->string('client_settings_modules_knowledgebase', 50)->nullable()->default('enabled')->comment('enabled|disabled');
			$table->string('client_settings_modules_estimates', 50)->nullable()->default('enabled')->comment('enabled|disabled');
			$table->string('client_settings_modules_subscriptions', 50)->nullable()->default('enabled')->comment('enabled|disabled');
			$table->string('client_settings_modules_tickets', 50)->nullable()->default('enabled')->comment('enabled|disabled');
			$table->string('client_import_first_name', 100)->nullable()->comment('used during import');
			$table->string('client_import_last_name', 100)->nullable()->comment('used during import');
			$table->string('client_import_email', 100)->nullable()->comment('used during import');
			$table->string('client_import_job_title', 100)->nullable()->comment('used during import');
			$table->text('client_custom_field_1')->nullable();
			$table->text('client_custom_field_2')->nullable();
			$table->text('client_custom_field_3')->nullable();
			$table->text('client_custom_field_4')->nullable();
			$table->text('client_custom_field_5')->nullable();
			$table->text('client_custom_field_6')->nullable();
			$table->text('client_custom_field_7')->nullable();
			$table->text('client_custom_field_8')->nullable();
			$table->text('client_custom_field_9')->nullable();
			$table->text('client_custom_field_10')->nullable();
			$table->dateTime('client_custom_field_11')->nullable();
			$table->dateTime('client_custom_field_12')->nullable();
			$table->dateTime('client_custom_field_13')->nullable();
			$table->dateTime('client_custom_field_14')->nullable();
			$table->dateTime('client_custom_field_15')->nullable();
			$table->dateTime('client_custom_field_16')->nullable();
			$table->dateTime('client_custom_field_17')->nullable();
			$table->dateTime('client_custom_field_18')->nullable();
			$table->dateTime('client_custom_field_19')->nullable();
			$table->dateTime('client_custom_field_20')->nullable();
			$table->text('client_custom_field_21')->nullable();
			$table->text('client_custom_field_22')->nullable();
			$table->text('client_custom_field_23')->nullable();
			$table->text('client_custom_field_24')->nullable();
			$table->text('client_custom_field_25')->nullable();
			$table->text('client_custom_field_26')->nullable();
			$table->text('client_custom_field_27')->nullable();
			$table->text('client_custom_field_28')->nullable();
			$table->text('client_custom_field_29')->nullable();
			$table->text('client_custom_field_30')->nullable();
			$table->string('client_custom_field_31', 20)->nullable();
			$table->string('client_custom_field_32', 20)->nullable();
			$table->string('client_custom_field_33', 20)->nullable();
			$table->string('client_custom_field_34', 20)->nullable();
			$table->string('client_custom_field_35', 20)->nullable();
			$table->string('client_custom_field_36', 20)->nullable();
			$table->string('client_custom_field_37', 20)->nullable();
			$table->string('client_custom_field_38', 20)->nullable();
			$table->string('client_custom_field_39', 20)->nullable();
			$table->string('client_custom_field_40', 20)->nullable();
			$table->string('client_custom_field_41', 150)->nullable();
			$table->string('client_custom_field_42', 150)->nullable();
			$table->string('client_custom_field_43', 150)->nullable();
			$table->string('client_custom_field_44', 150)->nullable();
			$table->string('client_custom_field_45', 150)->nullable();
			$table->string('client_custom_field_46', 150)->nullable();
			$table->string('client_custom_field_47', 150)->nullable();
			$table->string('client_custom_field_48', 150)->nullable();
			$table->string('client_custom_field_49', 150)->nullable();
			$table->string('client_custom_field_50', 150)->nullable();
			$table->integer('client_custom_field_51')->nullable();
			$table->integer('client_custom_field_52')->nullable();
			$table->integer('client_custom_field_53')->nullable();
			$table->integer('client_custom_field_54')->nullable();
			$table->integer('client_custom_field_55')->nullable();
			$table->integer('client_custom_field_56')->nullable();
			$table->integer('client_custom_field_57')->nullable();
			$table->integer('client_custom_field_58')->nullable();
			$table->integer('client_custom_field_59')->nullable();
			$table->integer('client_custom_field_60')->nullable();
			$table->decimal('client_custom_field_61', 10)->nullable();
			$table->decimal('client_custom_field_62', 10)->nullable();
			$table->decimal('client_custom_field_63', 10)->nullable();
			$table->decimal('client_custom_field_64', 10)->nullable();
			$table->decimal('client_custom_field_65', 10)->nullable();
			$table->decimal('client_custom_field_66', 10)->nullable();
			$table->decimal('client_custom_field_67', 10)->nullable();
			$table->decimal('client_custom_field_68', 10)->nullable();
			$table->decimal('client_custom_field_69', 10)->nullable();
			$table->decimal('client_custom_field_70', 10)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('clients');
	}

}
