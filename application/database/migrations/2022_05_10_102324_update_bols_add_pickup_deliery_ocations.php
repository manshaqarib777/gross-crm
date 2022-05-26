<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBolsAddPickupDelieryOcations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("bols",function(Blueprint $table){
            $table->string('pickup_location')->nullable();
            $table->string('pickup_date')->nullable();
            $table->string('pickup_time')->nullable();
            $table->string('pickup_email')->nullable();
            $table->string('pickup_gstin')->nullable();
			$table->string('delivery_location')->nullable();
            $table->string('delivery_date')->nullable();
            $table->string('delivery_time')->nullable();
            $table->string('delivery_email')->nullable();
            $table->string('delivery_gstin')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('contact_details')->nullable();
            $table->string('cargo_commodity')->nullable();
            $table->string('cargo_weight')->nullable();
            $table->string('contact_mc_dot_number')->nullable();
            $table->string('contact_name')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_term')->nullable();
            $table->string('contact_fax')->nullable();
			$table->string('contact_address')->nullable();
            $table->string('contact_driver')->nullable();
            $table->string('contact_dispatcher')->nullable();            
            $table->string('contact_truck')->nullable();
            $table->string('contact_trailer')->nullable();
            $table->string('load_mode')->nullable();
            $table->string('load_trailer_type')->nullable();
            $table->string('load_trailer_size')->nullable();
            $table->string('load_linear_feet')->nullable();
            $table->string('load_temperature')->nullable();
            $table->string('load_pallet_case_count')->nullable();
            $table->string('load_hazmat')->nullable();
            $table->string('load_requirements')->nullable();
            $table->string('load_instructions')->nullable();
            $table->string('load_length')->nullable();
            $table->string('load_width')->nullable();
            $table->string('load_height')->nullable();
            $table->string('pickup_time')->nullable();
            $table->string('delivery_time')->nullable();
            $table->string('carrier_unloading')->nullable();
            $table->string('carrier_pallet_exchange')->nullable();
            $table->string('carrier_estimated_weight')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("bols",function(Blueprint $table){
            $table->dropColumn('pickup_location');
            $table->dropColumn('pickup_date');
            $table->dropColumn('pickup_time');
            $table->dropColumn('pickup_email');
            $table->dropColumn('pickup_gstin');
			$table->dropColumn('delivery_location');
            $table->dropColumn('delivery_date');
            $table->dropColumn('delivery_time');
            $table->dropColumn('delivery_email');
            $table->dropColumn('delivery_gstin');
            $table->dropColumn('contact_person');
            $table->dropColumn('contact_details');
            $table->dropColumn('cargo_commodity');
            $table->dropColumn('cargo_weight');
            $table->dropColumn('contact_mc_dot_number');
            $table->dropColumn('contact_name');
            $table->dropColumn('contact_phone');
            $table->dropColumn('contact_term');
            $table->dropColumn('contact_fax');
            $table->dropColumn('contact_address');
            $table->dropColumn('contact_driver');
            $table->dropColumn('contact_dispatcher');
            $table->dropColumn('contact_truck');
            $table->dropColumn('contact_trailer');
            $table->dropColumn('load_mode');
            $table->dropColumn('load_trailer_type');
            $table->dropColumn('load_trailer_size');
            $table->dropColumn('load_linear_feet');
            $table->dropColumn('load_temperature');
            $table->dropColumn('load_pallet_case_count');
            $table->dropColumn('load_hazmat');
            $table->dropColumn('load_requirements');
            $table->dropColumn('load_instructions');
            $table->dropColumn('load_length');
            $table->dropColumn('load_width');
            $table->dropColumn('load_height');
            $table->dropColumn('pickup_time');
            $table->dropColumn('delivery_time');
            $table->dropColumn('carrier_unloading');
            $table->dropColumn('carrier_pallet_exchange');
            $table->dropColumn('carrier_estimated_weight');

        });
    }
}
