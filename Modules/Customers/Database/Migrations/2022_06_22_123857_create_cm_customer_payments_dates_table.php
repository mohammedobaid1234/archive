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
            $table->string('contract_number')->nullable();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->string('label')->nullable();
            $table->unsignedBigInteger('payment_id')->nullable();
            $table->string('payment_type')->nullable();
            $table->bigInteger('amount')->default(0);
            $table->string('currency_id')->nullable();
            $table->date('due_date')->nullable();
            $table->enum('state', ['تم_السداد','لم_يتم_السداد'])->default('لم_يتم_السداد');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('currency_id')->references('id')->on('core_currencies');
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
