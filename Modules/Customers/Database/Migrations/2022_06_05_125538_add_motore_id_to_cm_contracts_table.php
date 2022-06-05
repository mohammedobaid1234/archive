<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMotoreIdToCmContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cm_contacts', function (Blueprint $table) {
            $table->unsignedBigInteger('motor_id')->nullable();
            $table->foreign('motor_id')->references('id')->on('cm_motors_for_customers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cm_contacts', function (Blueprint $table) {

        });
    }
}
