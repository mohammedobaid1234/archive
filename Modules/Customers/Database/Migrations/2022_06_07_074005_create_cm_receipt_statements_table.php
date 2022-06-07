<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCmReceiptStatementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cm_receipt_statements', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_number')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('employee_id')->nullable();

            $table->smallInteger('basic_amount')->default(0);
            $table->smallInteger('received_amount');
            $table->smallInteger('remaining_amount')->default(0);
            $table->string('currency_id')->nullable();


            $table->date('transaction_date')->nullable();
            $table->string('transaction_type')->default('دفعة');

            $table->enum('payment_method',['نقدا', 'شيك']);
            $table->string('check_number')->nullable();
            $table->unsignedBigInteger('bank_id')->nullable();
            $table->date('check_due_date')->nullable();

            $table->date('next_due_date')->nullable();
            $table->string('opposite')->nullable();
            $table->string('other_terms')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->foreign('customer_id')->references('id')->on('cm_customers');
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->foreign('created_by')->references('id')->on('users');
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
        Schema::dropIfExists('cm_receipt_statements');
    }
}
