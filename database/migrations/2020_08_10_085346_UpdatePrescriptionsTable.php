<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePrescriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->dropColumn('items_per_prescription');
            $table->dropColumn('medicine-1');
            $table->dropColumn('medicine-2');
            $table->dropColumn('medicine-3');
            $table->dropColumn('medicine-4');
            $table->dropColumn('medicine-5');
            $table->dropColumn('medicine-6');
            $table->dropColumn('medicine-7');
            $table->dropColumn('medicine-8');
            $table->dropColumn('medicine-9');
            $table->dropColumn('medicine-10');
            $table->dropColumn('medicine-11');
            $table->dropColumn('medicine-12');
            $table->dropColumn('medicine-13');
            $table->dropColumn('medicine-14');
            $table->dropColumn('medicine-15');
            $table->dropColumn('medicine-16');
            $table->dropColumn('medicine-17');
            $table->dropColumn('medicine-18');
            $table->dropColumn('medicine-19');
            $table->dropColumn('medicine-20');

            $table->string('name', 255);
            $table->string('text', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('prescriptions', function (Blueprint $table) {
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

            $table->dropColumn('name');
            $table->dropColumn('text');
        });
    }
}
