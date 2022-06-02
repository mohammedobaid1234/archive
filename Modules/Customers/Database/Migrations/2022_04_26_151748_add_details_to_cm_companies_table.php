<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDetailsToCmCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cm_companies', function (Blueprint $table) {
            $table->string('mobile_no')->unique()->nullable();
            $table->string('mobile_no_2')->unique()->nullable();
            $table->string('telephone')->unique()->nullable();
            $table->string('email')->unique()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cm_companies', function (Blueprint $table) {

        });
    }
}
