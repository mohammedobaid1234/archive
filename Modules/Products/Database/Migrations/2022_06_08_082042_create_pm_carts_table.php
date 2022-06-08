<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePmCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pm_carts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sale_invoice');
            $table->unsignedBigInteger('product_id');
            $table->smallInteger('price_of_unit')->default(0);
            $table->smallInteger('quantity')->default(0);
            $table->smallInteger('total')->default(0);
            $table->foreign('product_id')->references('id')->on('pm_products');
            $table->foreign('sale_invoice')->references('id')->on('cm_sales_invoices');
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
        Schema::dropIfExists('pm_carts');
    }
}
