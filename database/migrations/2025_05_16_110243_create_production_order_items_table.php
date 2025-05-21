<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductionOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('production_order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('production_order_id')->nullable();
            $table->integer('quantity')->default(0);
            $table->longText('comment')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('production_order_id')
                ->references('id')
                ->on('production_orders')
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
        Schema::dropIfExists('production_order_items');
    }
}
