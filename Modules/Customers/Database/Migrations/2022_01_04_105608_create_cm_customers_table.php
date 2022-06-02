<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCmCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cm_customers', function (Blueprint $table) {
            $table->id();

            $table->string('first_name');
            $table->string('father_name');
            $table->string('grandfather_name');
            $table->string('last_name');
            $table->string('full_name')->storedAs('CONCAT(first_name, " ", father_name, " ", grandfather_name, " ", last_name)');

            $table->enum('type', ['شخصي', 'شركة', 'تاجر'])->default('شخصي');

            $table->string('mobile_no')->unique();

            $table->unsignedBigInteger('province_id')->nullable();
            $table->string('address')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('created_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cm_customers');
    }
}
