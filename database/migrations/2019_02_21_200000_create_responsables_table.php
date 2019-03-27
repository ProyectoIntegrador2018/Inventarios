<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResponsablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('responsables', function (Blueprint $table) {
            
            $table->increments('id');
            
            $table->string('name')->nullable();
            $table->string('email')->nullable();

            $table->timestamps();

            $table->integer('applicant_id')->unsigned()->index();
            $table->foreign('applicant_id')->references('id')->on('applicants');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('responsables');
    }
}
