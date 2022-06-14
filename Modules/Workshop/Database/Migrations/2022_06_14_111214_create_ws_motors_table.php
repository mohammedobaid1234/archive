<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWsMotorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ws_motors', function (Blueprint $table) {
            $table->id();
            $table->string('type_of_motor')->nullable();
            $table->string('model_of_motor')->nullable();
            $table->string('motor_number')->nullable();
            $table->string('motor_capacity')->nullable();
            $table->string('type_of_engine')->nullable();
            $table->string('model_of_engine')->nullable();
            $table->string('engine_number')->nullable();
            $table->unsignedBigInteger('created_by');
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
        Schema::dropIfExists('ws_motors');
    }
}
