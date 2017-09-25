<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {

            $table->increments('id');
            $table->string('system_id')->unique();
            $table->timestamps();

            $table->string('patient_firstname')->nullable();
            $table->string('patient_lastname')->nullable();
            $table->integer('patient_document_number')->nullable();
            $table->string('patient_document_type')->nullable();
            $table->string('patient_gender')->nullable();
            $table->date('patient_birthdate')->nullable();
            $table->string('patient_nationality')->nullable();
            $table->string('patient_phone')->nullable();
            $table->string('patient_cellphone')->nullable();
            $table->boolean('patient_state')->nullable();
            $table->string('patient_medical_coverage')->nullable();
            $table->string('patient_medical_coverage_plan')->nullable();
            $table->string('patient_medical_coverage_number')->nullable();
            $table->string('patient_studies')->nullable();
            $table->boolean('patient_complete_studies')->nullable();
            $table->string('patient_ocupation')->nullable();
            $table->string('patient_civil_status')->nullable();
            $table->string('patient_email_1')->nullable();
            $table->string('patient_email_2')->nullable();
            $table->string('patient_email_3')->nullable();
            $table->string('consultant_firstname')->nullable();
            $table->string('consultant_lastname')->nullable();
            $table->string('consultant_relationship')->nullable();
            $table->string('consultant_street')->nullable();
            $table->string('consultant_number')->nullable();
            $table->string('consultant_flat')->nullable();
            $table->string('consultant_city')->nullable();
            $table->string('consultant_district')->nullable();
            $table->string('consultant_postal_code')->nullable();
            $table->string('significant_firstname_1')->nullable();
            $table->string('significant_firstname_2')->nullable();
            $table->string('significant_firstname_3')->nullable();
            $table->string('significant_lastname_1')->nullable();
            $table->string('significant_lastname_2')->nullable();
            $table->string('significant_lastname_3')->nullable();
            $table->string('significant_cellphone_1')->nullable();
            $table->string('significant_cellphone_2')->nullable();
            $table->string('significant_cellphone_3')->nullable();
            $table->string('significant_phone_1')->nullable();
            $table->string('significant_phone_2')->nullable();
            $table->string('significant_phone_3')->nullable();
            $table->string('significant_link_1')->nullable();
            $table->string('significant_link_2')->nullable();
            $table->string('significant_link_3')->nullable();
            $table->string('derivative_firstname')->nullable();
            $table->string('derivative_lastname')->nullable();
            $table->string('derivative_cellphone')->nullable();
            $table->string('derivative_phone')->nullable();
            $table->string('professional_name_1')->nullable();
            $table->string('professional_name_2')->nullable();
            $table->string('professional_name_3')->nullable();
            $table->string('professional_cellphone_1')->nullable();
            $table->string('professional_cellphone_2')->nullable();
            $table->string('professional_cellphone_3')->nullable();
            $table->string('professional_phone_1')->nullable();
            $table->string('professional_phone_2')->nullable();
            $table->string('professional_phone_3')->nullable();
            $table->string('doctor_firstname_1')->nullable();
            $table->string('doctor_firstname_2')->nullable();
            $table->string('doctor_firstname_3')->nullable();
            $table->string('doctor_lastname_1')->nullable();
            $table->string('doctor_lastname_2')->nullable();
            $table->string('doctor_lastname_3')->nullable();
            $table->string('doctor_cellphone_1')->nullable();
            $table->string('doctor_cellphone_2')->nullable();
            $table->string('doctor_cellphone_3')->nullable();
            $table->string('doctor_phone_1')->nullable();
            $table->string('doctor_phone_2')->nullable();
            $table->string('doctor_phone_3')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('patients');
    }
}
