<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditAddressOnPatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('patients', function($table)
        {
            $table->renameColumn('consultant_street', 'patient_street');
            $table->renameColumn('consultant_number', 'patient_number');
            $table->renameColumn('consultant_flat', 'patient_flat');
            $table->renameColumn('consultant_city', 'patient_city');
            $table->renameColumn('consultant_district', 'patient_district');
            $table->renameColumn('consultant_postal_code', 'patient_postal_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('patients', function($table)
        {
            $table->renameColumn('patient_street', 'consultant_street');
            $table->renameColumn('patient_number', 'consultant_number');
            $table->renameColumn('patient_flat', 'consultant_flat');
            $table->renameColumn('patient_city', 'consultant_city');
            $table->renameColumn('patient_district', 'consultant_district');
            $table->renameColumn('patient_postal_code', 'consultant_postal_code');
        });
    }
}
