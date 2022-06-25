<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCheckSiteToCmChecksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cm_checks', function (Blueprint $table) {
            $table->string('check_site')->nullable()->after('exchange_site');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cm_checks', function (Blueprint $table) {
            $table->dropColumn('check_site');
        });
    }
}
