<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMaintenanceDateToCrCarsMaintenancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cr_cars_maintenances', function (Blueprint $table) {
            $table->date('maintenance_date')->nullable()->after('car_id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cr_cars_maintenances', function (Blueprint $table) {
            $table->dropColumn('maintenance_date');

        });
    }
}
