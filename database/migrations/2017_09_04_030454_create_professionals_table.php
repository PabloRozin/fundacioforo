<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfessionalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('professionals', function (Blueprint $table) {

            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->timestamps();

            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->integer('document_number')->nullable();
            $table->string('document_type')->nullable();
            $table->string('cuit')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('profession')->nullable();
            $table->string('registration_number')->nullable();
            $table->string('insurance')->nullable();
            $table->string('email')->nullable();
            $table->string('phone_1')->nullable();
            $table->string('phone_info_1')->nullable();
            $table->string('phone_2')->nullable();
            $table->string('phone_info_2')->nullable();
            $table->string('phone_3')->nullable();
            $table->string('phone_info_3')->nullable();
            $table->string('team')->nullable();
            $table->boolean('state')->nullable();
            $table->date('admision_date')->nullable();
            $table->string('street_1')->nullable();
            $table->string('street_2')->nullable();
            $table->string('street_3')->nullable();
            $table->string('number_1')->nullable();
            $table->string('number_2')->nullable();
            $table->string('number_3')->nullable();
            $table->string('flat_1')->nullable();
            $table->string('flat_2')->nullable();
            $table->string('flat_3')->nullable();
            $table->string('city_1')->nullable();
            $table->string('city_2')->nullable();
            $table->string('city_3')->nullable();
            $table->string('district_1')->nullable();
            $table->string('district_2')->nullable();
            $table->string('district_3')->nullable();
            $table->string('postal_code_1')->nullable();
            $table->string('postal_code_2')->nullable();
            $table->string('postal_code_3')->nullable();
            $table->string('address_info_1')->nullable();
            $table->string('address_info_2')->nullable();
            $table->string('address_info_3')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('professionals');
    }
}
