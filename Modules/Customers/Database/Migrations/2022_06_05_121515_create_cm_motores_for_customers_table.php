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
        Schema::create('cm_motors_for_customers', function (Blueprint $table) {
            $table->id();
            $table->string('serial_number')->unique();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('motor_type');
            $table->string('motor_model');
            $table->smallInteger('motor_capacity');
            $table->smallInteger('motor_price');
            $table->text('other_details')->nullable();
            $table->string('currency_id')->nullable();
            $table->date('contract_starting_date')->nullable();
            $table->date('contract_ending_date')->nullable();
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->foreign('customer_id')->references('id')->on('cm_customers');
            $table->foreign('currency_id')->references('id')->on('core_currencies');
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
        Schema::dropIfExists('cm_motors_for_customers');
    }
}
