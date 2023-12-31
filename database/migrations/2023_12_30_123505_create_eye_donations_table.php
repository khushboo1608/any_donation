<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEyeDonationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eye_donations', function (Blueprint $table) {
            $table->string('eyedonation_id')->unique()->primary();
            $table->string('eyedonation_title');
            $table->string('eyedonation_type');
            $table->string('eyedonation_purpose');
            $table->string('eyedonation_achievement');
            $table->string('eyedonation_started_in');
            $table->string('eyedonation_size');
            $table->integer('service_needs_id');
            $table->integer('specific_needs_id');
            $table->string('eyedonation_number');
            $table->string('eyedonation_email');
            $table->string('eyedonation_address');
            $table->string('eyedonation_lat');
            $table->string('eyedonation_long');
            $table->string('state_id');
            $table->string('city_id');
            $table->string('eyedonation_history');
            $table->string('eyedonation_image')->nullable();
            $table->tinyInteger('eyedonation_status')->default('0')->comment('0: enable, 1:disable');
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
        Schema::dropIfExists('eye_donations');
    }


}
