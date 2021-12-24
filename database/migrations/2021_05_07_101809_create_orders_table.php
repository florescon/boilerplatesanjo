<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_order_id')->nullable();
            $table->string('slug')->unique()->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('departament_id')->nullable();
            $table->unsignedBigInteger('payment_method_id')->nullable();
            $table->longText('comment')->nullable();
            $table->date('date_entered')->nullable();
            $table->boolean('automatic_production')->default(false);
            $table->unsignedBigInteger('audi_id')->nullable();
            $table->boolean('from_store')->default(false);
            $table->unsignedBigInteger('cash_id')->nullable();
            $table->boolean('approved')->default(false);
            $table->string('status_delivery', 32);
            $table->tinyInteger('type')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('parent_order_id')
                ->references('id')
                ->on('orders')
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
        Schema::dropIfExists('orders');
    }
}
