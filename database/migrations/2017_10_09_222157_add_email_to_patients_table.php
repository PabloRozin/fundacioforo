<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEmailToPatientsTable extends Migration
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
            $table->string('consultant_email')->nullable();
            $table->string('significant_email_1')->nullable();
            $table->string('significant_email_2')->nullable();
            $table->string('significant_email_3')->nullable();
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
            $table->dropColumn('consultant_email');
            $table->dropColumn('significant_email_1');
            $table->dropColumn('significant_email_2');
            $table->dropColumn('significant_email_3');
        });
    }
}
