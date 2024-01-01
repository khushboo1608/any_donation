<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventPromotionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_promotions', function (Blueprint $table) {
            $table->string('event_promotions_id')->unique()->primary();
            $table->string('event_promotions_title');
            $table->string('event_promotions_type');
            $table->string('event_promotions_purpose');
            $table->string('event_promotions_number');
            $table->string('event_promotions_email');
            $table->string('event_promotions_address');
            $table->string('event_promotions_lat');
            $table->string('event_promotions_long');
            $table->string('state_id');
            $table->string('city_id');
            $table->string('event_promotions_history');
            $table->string('event_promotions_image')->nullable();
            $table->tinyInteger('event_promotions_status')->default('0')->comment('0: enable, 1:disable');
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
        Schema::dropIfExists('event_promotions');
    }
}
