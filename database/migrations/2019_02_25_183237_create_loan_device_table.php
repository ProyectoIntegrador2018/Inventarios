<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoanDeviceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_device', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('loan_id')->unsigned()->index();
            $table->foreign('loan_id')->references('id')->on('loans');
            
            $table->integer('device_id')->unsigned()->index();
            $table->foreign('device_id')->references('id')->on('devices');
            
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
        Schema::dropIfExists('loan_device');
    }
}
