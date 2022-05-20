<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateQuotesAddPickupDelieryOcations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("quotes",function(Blueprint $table){
            $table->string('pickup_location')->nullable();
            $table->string('pickup_telefax')->nullable();
            $table->string('pickup_phone')->nullable();
            $table->string('pickup_email')->nullable();
            $table->string('pickup_gstin')->nullable();
			$table->string('delivery_location')->nullable();
            $table->string('delivery_telefax')->nullable();
            $table->string('delivery_phone')->nullable();
            $table->string('delivery_email')->nullable();
            $table->string('delivery_gstin')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('contact_details')->nullable();
            $table->string('cargo_commodity')->nullable();
            $table->string('cargo_weight')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("quotes",function(Blueprint $table){
            $table->dropColumn('pickup_location');
            $table->dropColumn('pickup_telefax');
            $table->dropColumn('pickup_phone');
            $table->dropColumn('pickup_email');
            $table->dropColumn('pickup_gstin');
			$table->dropColumn('delivery_location');
            $table->dropColumn('delivery_telefax');
            $table->dropColumn('delivery_phone');
            $table->dropColumn('delivery_email');
            $table->dropColumn('delivery_gstin');
            $table->dropColumn('contact_person');
            $table->dropColumn('contact_details');
            $table->dropColumn('cargo_commodity');
            $table->dropColumn('cargo_weight');
        });
    }
}
