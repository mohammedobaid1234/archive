<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExExchangeBondsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ex_exchange_bonds', function (Blueprint $table) {
            $table->id();
            $table->string('bond_no')->nullable();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->enum('type', ['الصندوق','المدير']);
            $table->string('product')->nullable();
            $table->string('reasons')->nullable();
            $table->smallInteger('amount')->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->foreign('created_by')->references('id')->on('users');

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
        Schema::dropIfExists('ex_exchange_bonds');
    }
}
