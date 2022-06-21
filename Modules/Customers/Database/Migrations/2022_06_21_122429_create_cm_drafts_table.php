<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCmDraftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cm_drafts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('employee_id');
            $table->string('national_id')->nullable();
            $table->date('due_date')->nullable();
            $table->smallInteger('amount')->default(0);
            $table->string('address')->nullable();
            $table->string('sponsor_name')->nullable();
            $table->string('watch_first')->nullable();
            $table->string('watch_second')->nullable();
            $table->string('additional_details')->nullable();
            $table->string('currency_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('employee_id')->references('id')->on('employees');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cm_drafts');
    }
}
