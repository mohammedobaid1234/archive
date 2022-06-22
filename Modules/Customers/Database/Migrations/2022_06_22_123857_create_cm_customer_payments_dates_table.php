<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCmCustomerPaymentsDatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cm_customer_payments_dates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contract_id')->nullable();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->date('due_date')->nullable();
            $table->enum('state', ['تم_السداد','لم_يتم_السداد'])->default('لم_يتم_السداد');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('contract_id')->references('id')->on('cm_contacts');
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
        Schema::dropIfExists('cm_customer_payments_dates');
    }
}
