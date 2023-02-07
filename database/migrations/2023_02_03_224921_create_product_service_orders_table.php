<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductServiceOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_service_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('service_order_id')->nullable();
            $table->integer('quantity')->default(0);
            $table->longText('comment')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('service_order_id')
                ->references('id')
                ->on('service_orders')
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
        Schema::dropIfExists('product_service_orders');
    }
}
