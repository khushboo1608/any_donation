<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->integer('login_type')->default(1)->comment('1:Admin,2:User,3:NGO,4:Blood Bank');            
            $table->string('name')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('phone')->unique();
            $table->string('password')->nullable();
            $table->tinyInteger('is_verified')->default('0');
            $table->string('otp')->nullable();
            $table->string('address')->nullable();
            $table->string('lat')->nullable();
            $table->string('long')->nullable();
            $table->integer('age')->default(0);
            $table->tinyInteger('gender')->default('0')->comment('1:Male,2:Female');
            $table->string('state_id')->nullable();
            $table->string('city_id')->nullable();
            $table->string('profession')->nullable();
            $table->string('imageurl')->nullable(); 
            $table->string('blood_group')->nullable(); 
            $table->tinyInteger('is_interested')->default('0')->comment('0:No,1:Yes');
            $table->string('type_of_ngo')->nullable();
            $table->string('type_of_blood_bank')->nullable();
            $table->string('blood_bank_history')->nullable();
            $table->tinyInteger('is_disable')->default(0)->comment('0: enable, 1:disable');                   
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
