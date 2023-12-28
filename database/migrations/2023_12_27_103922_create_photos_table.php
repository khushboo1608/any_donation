<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('photos', function (Blueprint $table) {
            $table->string('photo_id')->unique()->primary();
            $table->string('ngo_bank_id');
            $table->string('photo_name');
            $table->string('photo_url');
            $table->tinyInteger('photo_type')->default(0)->comment('1: ngo, 2: blood_bank ');          
            $table->tinyInteger('photo_status')->default(0)->comment('0: enable, 1:disable'); 
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
        Schema::dropIfExists('photos');
    }
}
