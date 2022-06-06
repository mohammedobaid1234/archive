<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCmContractTable extends Migration
{
    public function up()
    {
        Schema::create('cm_contacts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_of_contract')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('category_of_contract')->references('id')->on('cm_categories_of_contracts');
            $table->foreign('customer_id')->references('id')->on('cm_customers');
            $table->foreign('created_by')->references('id')->on('users');
            $table->softDeletes('deleted_at');
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
        Schema::dropIfExists('cm_contacts');
    }
}
