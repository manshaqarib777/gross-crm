<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('settings', function(Blueprint $table)
		{
			$table->integer('settings_id', true);
			$table->dateTime('settings_created');
			$table->dateTime('settings_updated');
			$table->string('settings_type', 50)->nullable()->default('standalone')->comment('standalone|saas');
			$table->integer('settings_saas_tenant_id')->nullable();
			$table->string('settings_saas_status', 100)->nullable()->comment('unsubscribed|free-trial|awaiting-payment|failed|active|cancelled');
			$table->integer('settings_saas_package_id')->nullable();
			$table->string('settings_saas_onetimelogin_key', 100)->nullable();
			$table->string('settings_saas_onetimelogin_destination', 100)->nullable()->comment('home|payment');
			$table->integer('settings_saas_package_limits_clients')->nullable();
			$table->integer('settings_saas_package_limits_team')->nullable();
			$table->integer('settings_saas_package_limits_projects')->nullable();
			$table->text('settings_saas_notification_uniqueid')->nullable()->comment('(optional) unique identifier');
			$table->text('settings_saas_notification_body')->nullable()->comment('html body of promotion etc');
			$table->text('settings_saas_notification_read')->nullable()->comment('yes|no');
			$table->text('settings_saas_notification_action')->nullable()->comment('none|external-link|internal-link');
			$table->text('settings_saas_notification_action_url')->nullable();
			$table->dateTime('settings_installation_date')->comment('date the system was setup');
			$table->text('settings_version');
			$table->text('settings_purchase_code')->nullable()->comment('codecanyon code');
			$table->text('settings_company_name')->nullable();
			$table->text('settings_company_address_line_1')->nullable();
			$table->text('settings_company_state')->nullable();
			$table->text('settings_company_city')->nullable();
			$table->text('settings_company_zipcode')->nullable();
			$table->text('settings_company_country')->nullable();
			$table->text('settings_company_telephone')->nullable();
			$table->text('settings_company_customfield_1')->nullable();
			$table->text('settings_company_customfield_2')->nullable();
			$table->text('settings_company_customfield_3')->nullable();
			$table->text('settings_company_customfield_4')->nullable();
			$table->text('settings_clients_registration')->nullable()->comment('enabled | disabled');
			$table->text('settings_clients_shipping_address')->nullable()->comment('enabled | disabled');
			$table->string('settings_clients_disable_email_delivery', 40)->nullable()->default('disabled')->comment('enabled | disabled');
			$table->string('settings_clients_app_login', 40)->nullable()->default('enabled')->comment('enabled | disabled');
			$table->string('settings_customfields_display_leads', 40)->nullable()->default('toggled')->comment('toggled|expanded');
			$table->string('settings_customfields_display_clients', 40)->nullable()->default('toggled')->comment('toggled|expanded');
			$table->string('settings_customfields_display_projects', 40)->nullable()->default('toggled')->comment('toggled|expanded');
			$table->string('settings_customfields_display_tasks', 40)->nullable()->default('toggled')->comment('toggled|expanded');
			$table->text('settings_email_general_variables')->nullable()->comment('common variable displayed available in templates');
			$table->text('settings_email_from_address')->nullable();
			$table->text('settings_email_from_name')->nullable();
			$table->text('settings_email_server_type')->nullable()->comment('smtp|sendmail');
			$table->text('settings_email_smtp_host')->nullable();
			$table->text('settings_email_smtp_port')->nullable();
			$table->text('settings_email_smtp_username')->nullable();
			$table->text('settings_email_smtp_password')->nullable();
			$table->text('settings_email_smtp_encryption')->nullable()->comment('tls|ssl|starttls');
			$table->text('settings_estimates_default_terms_conditions')->nullable();
			$table->text('settings_estimates_prefix')->nullable();
			$table->string('settings_estimates_show_view_status', 40)->nullable()->default('yes')->comment('yes|no');
			$table->string('settings_modules_projects', 40)->nullable()->default('enabled')->comment('enabled|disabled');
			$table->string('settings_modules_tasks', 40)->nullable()->default('enabled')->comment('enabled|disabled');
			$table->string('settings_modules_invoices', 40)->nullable()->default('enabled')->comment('enabled|disabled (invoice, payments, products)');
			$table->string('settings_modules_payments', 40)->nullable()->default('enabled')->comment('enabled|disabled');
			$table->string('settings_modules_leads', 40)->nullable()->default('enabled')->comment('enabled|disabled');
			$table->string('settings_modules_knowledgebase', 40)->nullable()->default('enabled')->comment('enabled|disabled');
			$table->string('settings_modules_estimates', 40)->nullable()->default('enabled')->comment('enabled|disabled');
			$table->string('settings_modules_expenses', 40)->nullable()->default('enabled')->comment('enabled|disabled');
			$table->string('settings_modules_notes', 40)->nullable()->default('enabled')->comment('enabled|disabled');
			$table->string('settings_modules_subscriptions', 40)->nullable()->default('enabled')->comment('enabled|disabled');
			$table->string('settings_modules_contracts', 40)->nullable()->default('enabled')->comment('enabled|disabled');
			$table->string('settings_modules_proposals', 40)->nullable()->default('enabled')->comment('enabled|disabled');
			$table->string('settings_modules_tickets', 40)->nullable()->default('enabled')->comment('enabled|disabled');
			$table->string('settings_modules_timetracking', 40)->nullable()->default('enabled')->comment('enabled|disabled');
			$table->string('settings_modules_reminders', 40)->nullable()->default('enabled')->comment('enabled|disabled');
			$table->integer('settings_files_max_size_mb')->nullable()->default(300)->comment('maximum size in MB');
			$table->string('settings_knowledgebase_article_ordering', 40)->nullable()->default('name')->comment('name-asc|name-desc|date-asc|date-desc');
			$table->string('settings_knowledgebase_allow_guest_viewing', 40)->nullable()->default('no')->comment('yes | no');
			$table->text('settings_knowledgebase_external_pre_body')->nullable()->comment('for use when viewing externally, as guest');
			$table->text('settings_knowledgebase_external_post_body')->nullable()->comment('for use when viewing externally, as guest');
			$table->text('settings_knowledgebase_external_header')->nullable()->comment('for use when viewing externally, as guest');
			$table->text('settings_system_timezone')->nullable();
			$table->text('settings_system_date_format')->nullable()->comment('d-m-Y | d/m/Y | m-d-Y | m/d/Y | Y-m-d | Y/m/d | Y-d-m | Y/d/m');
			$table->text('settings_system_datepicker_format')->nullable()->comment('dd-mm-yyyy | mm-dd-yyyy');
			$table->text('settings_system_default_leftmenu')->nullable()->comment('collapsed | open');
			$table->text('settings_system_default_statspanel')->nullable()->comment('collapsed | open');
			$table->boolean('settings_system_pagination_limits')->nullable();
			$table->boolean('settings_system_kanban_pagination_limits')->nullable();
			$table->text('settings_system_currency_code')->nullable();
			$table->text('settings_system_currency_symbol')->nullable();
			$table->text('settings_system_currency_position')->nullable()->comment('left|right');
			$table->text('settings_system_decimal_separator')->nullable();
			$table->text('settings_system_thousand_separator')->nullable();
			$table->string('settings_system_close_modals_body_click', 40)->nullable()->default('no')->comment('yes|no');
			$table->string('settings_system_language_default', 40)->nullable()->default('en')->comment('english|french|etc');
			$table->string('settings_system_language_allow_users_to_change', 40)->nullable()->default('yes')->comment('yes|no');
			$table->string('settings_system_logo_large_name', 40)->nullable()->default('logo.jpg');
			$table->string('settings_system_logo_small_name', 40)->nullable()->default('logo-small.jpg');
			$table->string('settings_system_logo_versioning', 40)->nullable()->default('1')->comment('used to refresh logo when updated');
			$table->string('settings_system_session_login_popup', 40)->nullable()->default('disabled')->comment('enabled|disabled');
			$table->date('settings_system_javascript_versioning')->nullable();
			$table->string('settings_tags_allow_users_create', 40)->nullable()->default('yes')->comment('yes|no');
			$table->string('settings_leads_allow_private', 40)->nullable()->default('yes')->comment('yes|no');
			$table->string('settings_leads_allow_new_sources', 40)->nullable()->default('yes')->comment('yes|no');
			$table->text('settings_leads_kanban_value')->nullable()->comment('show|hide');
			$table->text('settings_leads_kanban_date_created')->nullable()->comment('show|hide');
			$table->text('settings_leads_kanban_category')->nullable()->comment('show|hide');
			$table->text('settings_leads_kanban_date_contacted')->nullable()->comment('show|hide');
			$table->text('settings_leads_kanban_telephone')->nullable()->comment('show|hide');
			$table->text('settings_leads_kanban_source')->nullable()->comment('show|hide');
			$table->text('settings_leads_kanban_email')->nullable()->comment('show|hide');
			$table->text('settings_tasks_client_visibility')->nullable()->comment('visible|invisible - used in create new task form on the checkbox ');
			$table->text('settings_tasks_billable')->nullable()->comment('billable|not-billable - used in create new task form on the checkbox ');
			$table->text('settings_tasks_kanban_date_created')->nullable()->comment('show|hide');
			$table->text('settings_tasks_kanban_date_due')->nullable()->comment('show|hide');
			$table->text('settings_tasks_kanban_date_start')->nullable()->comment('show|hide');
			$table->text('settings_tasks_kanban_priority')->nullable()->comment('show|hide');
			$table->text('settings_tasks_kanban_client_visibility')->nullable()->comment('show|hide');
			$table->string('settings_tasks_kanban_project_title', 40)->nullable()->default('show')->comment('show|hide');
			$table->string('settings_tasks_kanban_client_name', 40)->nullable()->default('show')->comment('show|hide');
			$table->string('settings_tasks_send_overdue_reminder', 40)->nullable()->default('yes')->comment('yes|no');
			$table->text('settings_invoices_prefix')->nullable();
			$table->smallInteger('settings_invoices_recurring_grace_period')->nullable()->comment('Number of days for due date on recurring invoices. If set to zero, invoices will be given due date same as invoice date');
			$table->text('settings_invoices_default_terms_conditions')->nullable();
			$table->text('settings_invoices_show_view_status');
			$table->string('settings_projects_cover_images', 40)->nullable()->default('disabled')->comment('enabled|disabled');
			$table->string('settings_projects_permissions_basis', 40)->nullable()->default('user_roles')->comment('user_roles|category_based');
			$table->string('settings_projects_categories_main_menu', 40)->nullable()->default('no')->comment('yes|no');
			$table->decimal('settings_projects_default_hourly_rate', 10)->nullable()->default(0.00)->comment('default hourly rate for new projects');
			$table->text('settings_projects_allow_setting_permission_on_project_creation')->nullable()->comment('yes|no');
			$table->text('settings_projects_clientperm_files_view')->nullable()->comment('yes|no');
			$table->text('settings_projects_clientperm_files_upload')->nullable()->comment('yes|no');
			$table->text('settings_projects_clientperm_comments_view')->nullable()->comment('yes|no');
			$table->text('settings_projects_clientperm_comments_post')->nullable()->comment('yes|no');
			$table->text('settings_projects_clientperm_tasks_view')->nullable()->comment('yes|no');
			$table->text('settings_projects_clientperm_tasks_collaborate')->nullable()->comment('yes|no');
			$table->text('settings_projects_clientperm_tasks_create')->nullable()->comment('yes|no');
			$table->text('settings_projects_clientperm_timesheets_view')->nullable()->comment('yes|no');
			$table->text('settings_projects_clientperm_expenses_view')->nullable()->comment('yes|no');
			$table->text('settings_projects_clientperm_milestones_view')->nullable()->comment('yes|no');
			$table->text('settings_projects_clientperm_assigned_view')->nullable()->comment('yes|no');
			$table->text('settings_projects_assignedperm_milestone_manage')->nullable()->comment('yes|no');
			$table->text('settings_projects_assignedperm_tasks_collaborate')->nullable()->comment('yes|no');
			$table->text('settings_stripe_secret_key')->nullable();
			$table->text('settings_stripe_public_key')->nullable();
			$table->text('settings_stripe_webhooks_key')->nullable()->comment('from strip dashboard');
			$table->text('settings_stripe_default_subscription_plan_id')->nullable();
			$table->text('settings_stripe_currency')->nullable();
			$table->text('settings_stripe_display_name')->nullable()->comment('what customer will see on payment screen');
			$table->text('settings_stripe_status')->nullable()->comment('enabled|disabled');
			$table->string('settings_subscriptions_prefix', 40)->nullable()->default('SUB-');
			$table->text('settings_paypal_email')->nullable();
			$table->text('settings_paypal_currency')->nullable();
			$table->text('settings_paypal_display_name')->nullable()->comment('what customer will see on payment screen');
			$table->text('settings_paypal_mode')->nullable()->comment('sandbox | live');
			$table->text('settings_paypal_status')->nullable()->comment('enabled|disabled');
			$table->text('settings_mollie_live_api_key')->nullable();
			$table->text('settings_mollie_test_api_key')->nullable();
			$table->text('settings_mollie_display_name')->nullable();
			$table->string('settings_mollie_mode', 40)->nullable()->default('live');
			$table->text('settings_mollie_currency')->nullable();
			$table->string('settings_mollie_status', 40)->nullable()->default('disabled')->comment('enabled|disabled');
			$table->text('settings_bank_details')->nullable();
			$table->text('settings_bank_display_name')->nullable()->comment('what customer will see on payment screen');
			$table->text('settings_bank_status')->nullable()->comment('enabled|disabled');
			$table->text('settings_razorpay_keyid')->nullable();
			$table->text('settings_razorpay_secretkey')->nullable();
			$table->text('settings_razorpay_currency')->nullable();
			$table->text('settings_razorpay_display_name')->nullable();
			$table->string('settings_razorpay_status', 40)->nullable()->default('disabled');
			$table->string('settings_completed_check_email', 40)->nullable()->default('no')->comment('yes|no');
			$table->string('settings_expenses_billable_by_default', 40)->nullable()->default('yes')->comment('yes|no');
			$table->text('settings_tickets_edit_subject')->nullable()->comment('yes|no');
			$table->text('settings_tickets_edit_body')->nullable()->comment('yes|no');
			$table->string('settings_theme_name', 40)->nullable()->default('default')->comment('default|darktheme');
			$table->text('settings_theme_head')->nullable();
			$table->text('settings_theme_body')->nullable();
			$table->text('settings_track_thankyou_session_id')->nullable()->comment('used to ensure we show thank you page just once');
			$table->string('settings_cronjob_has_run', 40)->nullable()->default('no')->comment('yes|no');
			$table->dateTime('settings_cronjob_last_run')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('settings');
	}

}
