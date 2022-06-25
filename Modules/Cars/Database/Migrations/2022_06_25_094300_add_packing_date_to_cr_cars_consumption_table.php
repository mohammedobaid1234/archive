<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPackingDateToCrCarsConsumptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cr_cars_consumption', function (Blueprint $table) {
            $table->date('packing_date')->nullable()->after('amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cr_cars_consumption', function (Blueprint $table) {
            $table->dropColumn('packing_date');
        });
    }
}
