<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->string('video_id')->unique()->primary();
            $table->string('ngo_bank_id');
            $table->string('video_url');
            $table->tinyInteger('video_type')->default(0)->comment('1: ngo, 2: blood_bank ');          
            $table->tinyInteger('video_status')->default(0)->comment('0: enable, 1:disable'); 
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
        Schema::dropIfExists('videos');
    }
}
