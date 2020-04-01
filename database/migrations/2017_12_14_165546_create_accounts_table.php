<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->char('accountName', 255)->unique();
            $table->boolean('state');
            $table->boolean('accepted_conditions');
            $table->integer('professionals_limit');
            $table->integer('patients_limit');
            $table->integer('administrator_limit');
            $table->char('logo', 255);
            $table->timestamps();
        });

        Schema::table('users', function ($table) {
            $table->integer('account_id');
        });

        Schema::table('patients', function ($table) {
            $table->integer('account_id');
        });

        Schema::table('patient_admissions', function ($table) {
            $table->integer('account_id');
        });

        Schema::table('professionals', function ($table) {
            $table->integer('account_id');
        });

        Schema::table('hc_dates', function ($table) {
            $table->integer('account_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('accounts');

        Schema::table('users', function ($table) {
            $table->dropColumn('account_id');
        });

        Schema::table('patients', function ($table) {
            $table->dropColumn('account_id');
        });

        Schema::table('patient_admissions', function ($table) {
            $table->dropColumn('account_id');
        });

        Schema::table('professionals', function ($table) {
            $table->dropColumn('account_id');
        });

        Schema::table('hc_dates', function ($table) {
            $table->dropColumn('account_id');
        });
    }
}
