<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHcDatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hc_dates', function (Blueprint $table) {

            $table->increments('id');
            $table->timestamps();

            $table->integer('patient_id')->nullable();
            $table->integer('professional_id')->nullable();

            $table->text('type_info')->nullable();
            $table->text('detail')->nullable();
            $table->string('file_1')->nullable();
            $table->string('file_2')->nullable();
            $table->string('file_3')->nullable();
            $table->string('type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('hc_dates');
    }
}
