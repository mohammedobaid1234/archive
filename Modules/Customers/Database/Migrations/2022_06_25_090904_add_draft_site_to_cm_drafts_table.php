<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDraftSiteToCmDraftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cm_drafts', function (Blueprint $table) {
            $table->string('draft_site')->nullable()->after('due_date');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cm_drafts', function (Blueprint $table) {
            $table->dropColumn('draft_site');
        });
    }
}
