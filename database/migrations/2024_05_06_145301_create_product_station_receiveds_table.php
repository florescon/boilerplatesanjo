<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductStationReceivedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_station_receiveds', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('order_id')->nullable();
            $table->unsignedBigInteger('product_station_id')->nullable();
            $table->integer('quantity')->default(0);
            $table->longText('comment')->nullable();
            $table->unsignedBigInteger('created_audi_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('product_station_id')
                ->references('id')
                ->on('product_stations')
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
        Schema::dropIfExists('product_station_receiveds');
    }
}