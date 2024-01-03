<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCrowdFundingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crowd_fundings', function (Blueprint $table) {
            $table->string('crowdfundings_id')->unique()->primary();
            $table->string('crowdfundings_title');
            $table->string('crowdfundings_single_image');
            $table->string('crowdfundings_multi_image');
            $table->string('crowdfundings_type');
            $table->string('crowdfundings_purpose');
            $table->string('crowdfundings_issue');
            $table->string('crowdfundings_amount');
            $table->string('crowdfundings_patient1_name');
            $table->string('crowdfundings_patient1_age');
            $table->string('crowdfundings_patient1_image');
            $table->string('crowdfundings_patient2_name');
            $table->string('crowdfundings_patient2_age');
            $table->string('crowdfundings_patient2_image');
            $table->string('crowdfundings_medical_certificate');
            $table->string('crowdfundings_address');
            $table->string('crowdfundings_lat');
            $table->string('crowdfundings_long');
            $table->string('state_id');
            $table->string('city_id');
            $table->string('crowdfundings_account_number');
            $table->string('crowdfundings_account_holder_name');
            $table->string('crowdfundings_ifsc_code');
            $table->string('crowdfundings_upi_number');
            $table->string('crowdfundings_gpay_number');
            $table->string('crowdfundings_status')->default('0')->comment('0: enable, 1:disable');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('crowd_fundings');
    }
}
