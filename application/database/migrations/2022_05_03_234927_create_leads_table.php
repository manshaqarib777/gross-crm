<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('leads', function(Blueprint $table)
		{
			$table->integer('lead_id', true);
			$table->string('lead_importid', 100)->nullable();
			$table->float('lead_position', 10, 0);
			$table->dateTime('lead_created')->nullable();
			$table->dateTime('lead_updated')->nullable();
			$table->integer('lead_creatorid')->nullable()->index('lead_creatorid');
			$table->integer('lead_updatorid')->nullable();
			$table->integer('lead_categoryid')->nullable()->default(3)->index('lead_categoryid');
			$table->string('lead_firstname', 100)->nullable();
			$table->string('lead_lastname', 100)->nullable();
			$table->string('lead_email', 150)->nullable()->index('lead_email');
			$table->string('lead_phone', 150)->nullable();
			$table->string('lead_job_position', 150)->nullable();
			$table->string('lead_company_name', 150)->nullable();
			$table->string('lead_website', 150)->nullable();
			$table->string('lead_street', 150)->nullable();
			$table->string('lead_city', 150)->nullable();
			$table->string('lead_state', 150)->nullable();
			$table->string('lead_zip', 150)->nullable();
			$table->string('lead_country', 150)->nullable();
			$table->string('lead_source', 150)->nullable();
			$table->string('lead_title', 250)->nullable();
			$table->text('lead_description')->nullable();
			$table->decimal('lead_value', 10)->nullable();
			$table->date('lead_last_contacted')->nullable();
			$table->string('lead_converted', 10)->nullable()->default('no')->comment('yes|no');
			$table->integer('lead_converted_by_userid')->nullable()->comment('id of user who converted');
			$table->dateTime('lead_converted_date')->nullable()->comment('date lead converted');
			$table->integer('lead_converted_clientid')->nullable()->index('lead_converted_clientid')->comment('if the lead has previously been converted to a client');
			$table->boolean('lead_status')->nullable()->default(1)->index('lead_status')->comment('Deafult is id: 1 (leads_status) table');
			$table->string('lead_active_state', 10)->nullable()->default('active')->comment('active|archived');
			$table->string('lead_visibility', 40)->nullable()->default('visible')->comment('visible|hidden (used to prevent tasks that are still being cloned from showing in tasks list)');
			$table->text('lead_custom_field_1')->nullable();
			$table->text('lead_custom_field_2')->nullable();
			$table->text('lead_custom_field_3')->nullable();
			$table->text('lead_custom_field_4')->nullable();
			$table->text('lead_custom_field_5')->nullable();
			$table->text('lead_custom_field_6')->nullable();
			$table->text('lead_custom_field_7')->nullable();
			$table->text('lead_custom_field_8')->nullable();
			$table->text('lead_custom_field_9')->nullable();
			$table->text('lead_custom_field_10')->nullable();
			$table->text('lead_custom_field_11')->nullable();
			$table->text('lead_custom_field_12')->nullable();
			$table->text('lead_custom_field_13')->nullable();
			$table->text('lead_custom_field_14')->nullable();
			$table->text('lead_custom_field_15')->nullable();
			$table->text('lead_custom_field_16')->nullable();
			$table->text('lead_custom_field_17')->nullable();
			$table->text('lead_custom_field_18')->nullable();
			$table->text('lead_custom_field_19')->nullable();
			$table->text('lead_custom_field_20')->nullable();
			$table->text('lead_custom_field_21')->nullable();
			$table->text('lead_custom_field_22')->nullable();
			$table->text('lead_custom_field_23')->nullable();
			$table->text('lead_custom_field_24')->nullable();
			$table->text('lead_custom_field_25')->nullable();
			$table->text('lead_custom_field_26')->nullable();
			$table->text('lead_custom_field_27')->nullable();
			$table->text('lead_custom_field_28')->nullable();
			$table->text('lead_custom_field_29')->nullable();
			$table->text('lead_custom_field_30')->nullable();
			$table->dateTime('lead_custom_field_31')->nullable();
			$table->dateTime('lead_custom_field_32')->nullable();
			$table->dateTime('lead_custom_field_33')->nullable();
			$table->dateTime('lead_custom_field_34')->nullable();
			$table->dateTime('lead_custom_field_35')->nullable();
			$table->dateTime('lead_custom_field_36')->nullable();
			$table->dateTime('lead_custom_field_37')->nullable();
			$table->dateTime('lead_custom_field_38')->nullable();
			$table->dateTime('lead_custom_field_39')->nullable();
			$table->dateTime('lead_custom_field_40')->nullable();
			$table->text('lead_custom_field_41')->nullable();
			$table->text('lead_custom_field_42')->nullable();
			$table->text('lead_custom_field_43')->nullable();
			$table->text('lead_custom_field_44')->nullable();
			$table->text('lead_custom_field_45')->nullable();
			$table->text('lead_custom_field_46')->nullable();
			$table->text('lead_custom_field_47')->nullable();
			$table->text('lead_custom_field_48')->nullable();
			$table->text('lead_custom_field_49')->nullable();
			$table->text('lead_custom_field_50')->nullable();
			$table->text('lead_custom_field_51')->nullable();
			$table->text('lead_custom_field_52')->nullable();
			$table->text('lead_custom_field_53')->nullable();
			$table->text('lead_custom_field_54')->nullable();
			$table->text('lead_custom_field_55')->nullable();
			$table->text('lead_custom_field_56')->nullable();
			$table->text('lead_custom_field_57')->nullable();
			$table->text('lead_custom_field_58')->nullable();
			$table->text('lead_custom_field_59')->nullable();
			$table->text('lead_custom_field_60')->nullable();
			$table->string('lead_custom_field_61', 20)->nullable();
			$table->string('lead_custom_field_62', 20)->nullable();
			$table->string('lead_custom_field_63', 20)->nullable();
			$table->string('lead_custom_field_64', 20)->nullable();
			$table->string('lead_custom_field_65', 20)->nullable();
			$table->string('lead_custom_field_66', 20)->nullable();
			$table->string('lead_custom_field_67', 20)->nullable();
			$table->string('lead_custom_field_68', 20)->nullable();
			$table->string('lead_custom_field_69', 20)->nullable();
			$table->string('lead_custom_field_70', 20)->nullable();
			$table->string('lead_custom_field_71', 20)->nullable();
			$table->string('lead_custom_field_72', 20)->nullable();
			$table->string('lead_custom_field_73', 20)->nullable();
			$table->string('lead_custom_field_74', 20)->nullable();
			$table->string('lead_custom_field_75', 20)->nullable();
			$table->string('lead_custom_field_76', 20)->nullable();
			$table->string('lead_custom_field_77', 20)->nullable();
			$table->string('lead_custom_field_78', 20)->nullable();
			$table->string('lead_custom_field_79', 20)->nullable();
			$table->string('lead_custom_field_80', 20)->nullable();
			$table->string('lead_custom_field_81', 150)->nullable();
			$table->string('lead_custom_field_82', 150)->nullable();
			$table->string('lead_custom_field_83', 150)->nullable();
			$table->string('lead_custom_field_84', 150)->nullable();
			$table->string('lead_custom_field_85', 150)->nullable();
			$table->string('lead_custom_field_86', 150)->nullable();
			$table->string('lead_custom_field_87', 150)->nullable();
			$table->string('lead_custom_field_88', 150)->nullable();
			$table->string('lead_custom_field_89', 150)->nullable();
			$table->string('lead_custom_field_90', 150)->nullable();
			$table->string('lead_custom_field_91', 150)->nullable();
			$table->string('lead_custom_field_92', 150)->nullable();
			$table->string('lead_custom_field_93', 150)->nullable();
			$table->string('lead_custom_field_94', 150)->nullable();
			$table->string('lead_custom_field_95', 150)->nullable();
			$table->string('lead_custom_field_96', 150)->nullable();
			$table->string('lead_custom_field_97', 150)->nullable();
			$table->string('lead_custom_field_98', 150)->nullable();
			$table->string('lead_custom_field_99', 150)->nullable();
			$table->string('lead_custom_field_100', 150)->nullable();
			$table->string('lead_custom_field_101', 150)->nullable();
			$table->string('lead_custom_field_102', 150)->nullable();
			$table->string('lead_custom_field_103', 150)->nullable();
			$table->string('lead_custom_field_104', 150)->nullable();
			$table->string('lead_custom_field_105', 150)->nullable();
			$table->string('lead_custom_field_106', 150)->nullable();
			$table->string('lead_custom_field_107', 150)->nullable();
			$table->string('lead_custom_field_108', 150)->nullable();
			$table->string('lead_custom_field_109', 150)->nullable();
			$table->string('lead_custom_field_110', 150)->nullable();
			$table->integer('lead_custom_field_111')->nullable();
			$table->integer('lead_custom_field_112')->nullable();
			$table->integer('lead_custom_field_113')->nullable();
			$table->integer('lead_custom_field_114')->nullable();
			$table->integer('lead_custom_field_115')->nullable();
			$table->integer('lead_custom_field_116')->nullable();
			$table->integer('lead_custom_field_117')->nullable();
			$table->integer('lead_custom_field_118')->nullable();
			$table->integer('lead_custom_field_119')->nullable();
			$table->integer('lead_custom_field_120')->nullable();
			$table->integer('lead_custom_field_121')->nullable();
			$table->integer('lead_custom_field_122')->nullable();
			$table->integer('lead_custom_field_123')->nullable();
			$table->integer('lead_custom_field_124')->nullable();
			$table->integer('lead_custom_field_125')->nullable();
			$table->integer('lead_custom_field_126')->nullable();
			$table->integer('lead_custom_field_127')->nullable();
			$table->integer('lead_custom_field_128')->nullable();
			$table->integer('lead_custom_field_129')->nullable();
			$table->integer('lead_custom_field_130')->nullable();
			$table->decimal('lead_custom_field_131', 10)->nullable();
			$table->decimal('lead_custom_field_132', 10)->nullable();
			$table->decimal('lead_custom_field_133', 10)->nullable();
			$table->decimal('lead_custom_field_134', 10)->nullable();
			$table->decimal('lead_custom_field_135', 10)->nullable();
			$table->decimal('lead_custom_field_136', 10)->nullable();
			$table->decimal('lead_custom_field_137', 10)->nullable();
			$table->decimal('lead_custom_field_138', 10)->nullable();
			$table->decimal('lead_custom_field_139', 10)->nullable();
			$table->decimal('lead_custom_field_140', 10)->nullable();
			$table->decimal('lead_custom_field_141', 10)->nullable();
			$table->decimal('lead_custom_field_142', 10)->nullable();
			$table->decimal('lead_custom_field_143', 10)->nullable();
			$table->decimal('lead_custom_field_144', 10)->nullable();
			$table->decimal('lead_custom_field_145', 10)->nullable();
			$table->decimal('lead_custom_field_146', 10)->nullable();
			$table->decimal('lead_custom_field_147', 10)->nullable();
			$table->decimal('lead_custom_field_148', 10)->nullable();
			$table->decimal('lead_custom_field_149', 10)->nullable();
			$table->decimal('lead_custom_field_150', 10)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('leads');
	}

}
