<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNgosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('ngos', function (Blueprint $table) {
            $table->string('ngo_id')->unique()->primary();
            $table->integer('user_id');
            $table->string('account_number')->nullable();
            $table->string('account_holder_name')->nullable();
            $table->string('ifsc_code')->nullable();
            $table->string('upi_number')->nullable();
            $table->string('gpay_number')->nullable();
            $table->string('address_proof')->nullable();
            $table->string('jj_act')->nullable();
            $table->string('form_10_b')->nullable();
            $table->string('a12_certificate')->nullable();
            $table->string('cancelled_blank_cheque')->nullable();
            $table->string('pan_of_ngo')->nullable();
            $table->string('registration_certificate')->nullable();
            $table->string('achievements')->nullable();
            $table->string('history')->nullable();
            $table->string('purpose')->nullable();
            $table->string('started_in')->nullable();
            $table->string('size')->nullable();
            $table->text('specific_needs_id')->nullable();
            $table->text('service_needs_id')->nullable();
            $table->tinyInteger('ngo_status')->default(0)->comment('0: enable, 1:disable'); 
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
        Schema::dropIfExists('ngos');
    }
}
