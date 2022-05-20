<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->dateTime('created')->nullable();
			$table->dateTime('updated')->nullable();
			$table->dateTime('deleted')->nullable()->comment('date when acccount was deleted');
			$table->integer('creatorid')->nullable();
			$table->string('email')->nullable()->index('email');
			$table->string('password');
			$table->string('first_name', 100);
			$table->string('last_name', 100);
			$table->string('phone', 100)->nullable();
			$table->string('position')->nullable();
			$table->integer('clientid')->nullable()->index('clientid')->comment('for client users');
			$table->string('account_owner', 10)->nullable()->default('no')->index('primary_contact')->comment('yes | no');
			$table->string('primary_admin', 10)->nullable()->default('no')->comment('yes | no (only 1 primary admin - created during setup)');
			$table->string('avatar_directory', 100)->nullable();
			$table->string('avatar_filename', 100)->nullable();
			$table->string('type', 10)->index('type')->comment('client | team |contact');
			$table->string('status', 20)->nullable()->default('active')->comment('active|suspended|deleted');
			$table->integer('role_id')->default(2)->index('role_id')->comment('for team users');
			$table->dateTime('last_seen')->nullable();
			$table->string('theme', 100)->nullable()->default('default');
			$table->string('last_ip_address', 100)->nullable();
			$table->string('social_facebook', 200)->nullable();
			$table->string('social_twitter', 200)->nullable();
			$table->string('social_linkedin', 200)->nullable();
			$table->string('social_github', 200)->nullable();
			$table->string('social_dribble', 200)->nullable();
			$table->string('pref_language', 200)->nullable()->default('english')->comment('english|french|etc');
			$table->string('pref_email_notifications', 10)->nullable()->default('yes')->comment('yes | no');
			$table->string('pref_leftmenu_position', 50)->nullable()->default('collapsed')->comment('collapsed | open');
			$table->string('pref_statspanel_position', 50)->nullable()->default('collapsed')->comment('collapsed | open');
			$table->string('pref_filter_own_tasks', 50)->nullable()->default('no')->comment('Show only a users tasks in the tasks list');
			$table->string('pref_filter_own_projects', 50)->nullable()->default('no')->comment('Show only a users projects in the projects list');
			$table->string('pref_filter_show_archived_projects', 50)->nullable()->default('no')->comment('Show archived projects');
			$table->string('pref_filter_show_archived_tasks', 50)->nullable()->default('no')->comment('Show archived projects');
			$table->string('pref_filter_show_archived_leads', 50)->nullable()->default('no')->comment('Show archived projects');
			$table->string('pref_filter_own_leads', 50)->nullable()->default('no')->comment('Show only a users projects in the leads list');
			$table->string('pref_view_tasks_layout', 50)->nullable()->default('kanban')->comment('list|kanban');
			$table->string('pref_view_leads_layout', 50)->nullable()->default('kanban')->comment('list|kanban');
			$table->string('pref_view_projects_layout', 50)->nullable()->default('list')->comment('list|card|milestone|pipeline|category|gnatt');
			$table->string('pref_theme', 100)->nullable()->default('default');
			$table->string('remember_token', 150)->nullable();
			$table->string('forgot_password_token', 150)->nullable()->comment('random token');
			$table->dateTime('forgot_password_token_expiry')->nullable();
			$table->string('force_password_change', 10)->nullable()->default('no')->comment('yes|no');
			$table->string('notifications_system', 10)->nullable()->default('no')->comment('no| yes | yes_email [everyone] NB: database defaults for all notifications are \'no\' actual values must be set in the settings config file');
			$table->string('notifications_new_project', 10)->nullable()->default('no')->comment('no| yes_email [client]');
			$table->string('notifications_projects_activity', 10)->nullable()->default('no')->comment('no | yes | yes_email [everyone]');
			$table->string('notifications_billing_activity', 10)->nullable()->default('no')->comment('no | yes | yes_email |[team]');
			$table->string('notifications_new_assignement', 10)->nullable()->default('no')->comment('no | yes | yes_email [team]');
			$table->string('notifications_leads_activity', 10)->nullable()->default('no')->comment('no | yes | yes_email [team]');
			$table->string('notifications_tasks_activity', 10)->nullable()->default('no')->comment('no | yes | yes_email  [everyone]');
			$table->string('notifications_tickets_activity', 10)->nullable()->default('no')->comment('no | yes | yes_email  [everyone]');
			$table->string('thridparty_stripe_customer_id', 150)->nullable()->comment('optional - when customer pays via ');
			$table->string('dashboard_access', 150)->nullable()->default('yes')->index('dashboard_access')->comment('yes|no');
			$table->string('welcome_email_sent', 150)->nullable()->default('no')->comment('yes|no');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}
