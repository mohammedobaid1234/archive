<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCmChecksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cm_checks', function (Blueprint $table) {
            $table->id();
            $table->string('check_number')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->smallInteger('amount')->default(0);
            $table->string('currency_id')->nullable();
            $table->enum('type', ['وارد','صادر']);
            $table->date('due_date')->nullable();
            
            $table->unsignedBigInteger('bank_id')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('customer_id')->references('id')->on('cm_customers');
            $table->foreign('bank_id')->references('id')->on('core_banks');
            $table->foreign('currency_id')->references('id')->on('core_currencies');
            $table->foreign('created_by')->references('id')->on('users');
            $table->text('additional_details')->nullable();
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
        Schema::dropIfExists('cm_checks');
    }
}
