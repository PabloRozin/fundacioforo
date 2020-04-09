<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrescriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('professional_id');
            $table->integer('patient_id');
            $table->integer('account_id');
            $table->integer('hc_date_id');
            $table->timestamps();

            $table->date('date')->nullable();
            $table->integer('items_per_prescription')->nullable();
            $table->string('medicine-1', 255)->nullable();
            $table->string('medicine-2', 255)->nullable();
            $table->string('medicine-3', 255)->nullable();
            $table->string('medicine-4', 255)->nullable();
            $table->string('medicine-5', 255)->nullable();
            $table->string('medicine-6', 255)->nullable();
            $table->string('medicine-7', 255)->nullable();
            $table->string('medicine-8', 255)->nullable();
            $table->string('medicine-9', 255)->nullable();
            $table->string('medicine-10', 255)->nullable();
            $table->string('medicine-11', 255)->nullable();
            $table->string('medicine-12', 255)->nullable();
            $table->string('medicine-13', 255)->nullable();
            $table->string('medicine-14', 255)->nullable();
            $table->string('medicine-15', 255)->nullable();
            $table->string('medicine-16', 255)->nullable();
            $table->string('medicine-17', 255)->nullable();
            $table->string('medicine-18', 255)->nullable();
            $table->string('medicine-19', 255)->nullable();
            $table->string('medicine-20', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('patients_admitions');
    }
}
