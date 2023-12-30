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
            $table->integer('user_id');
            $table->string('video_url');
            $table->tinyInteger('video_type')->default(0)->comment('3: ngo, 4: blood_bank');          
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
