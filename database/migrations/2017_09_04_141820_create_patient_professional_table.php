<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientProfessionalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patient_professional', function (Blueprint $table) {

            $table->integer('patient_id');
            $table->integer('professional_id');
            $table->timestamps();
            $table->primary(['patient_id', 'professional_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('patient_professional');
    }
}
