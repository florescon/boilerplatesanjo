<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaterialOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('material_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('product_order_id')->nullable();
            $table->unsignedBigInteger('material_id')->nullable();
            $table->decimal('price', 8, 2)->default(0)->nullable();
            $table->double('unit_quantity', 15, 8)->nullable();
            $table->double('quantity', 15, 8)->nullable();
            $table->unsignedBigInteger('audi_id')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->onDelete('cascade');

            $table->foreign('product_order_id')
                ->references('id')
                ->on('product_order')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('material_orders');
    }
}
