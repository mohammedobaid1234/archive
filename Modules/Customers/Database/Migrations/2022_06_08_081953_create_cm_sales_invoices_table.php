<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCmSalesInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cm_sales_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_number')->nullable();
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('customer_id');
            $table->string('address');
            $table->smallInteger('total')->default(0);
            $table->smallInteger('paid')->default(0);
            $table->smallInteger('remaining')->default(0);
            $table->unsignedBigInteger('created_by');
            $table->foreign('customer_id')->references('id')->on('cm_customers');
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->foreign('created_by')->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cm_sales_invoices');
    }
}
