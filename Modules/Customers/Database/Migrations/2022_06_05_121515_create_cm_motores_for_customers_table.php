<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCmMotoresForCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cm_products_for_customers', function (Blueprint $table) {
            $table->id();
            $table->string('contract_number')->unique();
            $table->string('serial_number')->unique();
            $table->unsignedBigInteger('contract_id')->nullable();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('product_type');
            $table->string('product_model');
            $table->smallInteger('product_capacity');
            $table->smallInteger('product_price');
            $table->text('other_details')->nullable();
            $table->string('currency_id')->nullable();
            $table->date('contract_starting_date')->nullable();
            $table->date('contract_ending_date')->nullable();
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->foreign('customer_id')->references('id')->on('cm_customers');
            $table->foreign('currency_id')->references('id')->on('core_currencies');
            $table->foreign('contract_id')->references('id')->on('cm_contacts');
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
        Schema::dropIfExists('cm_products_for_customers');
    }
}
