<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductStationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_stations', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('order_id')->nullable();
            $table->unsignedBigInteger('station_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedInteger('status_id')->nullable();
            $table->unsignedBigInteger('personal_id')->nullable();
            $table->longText('comment')->nullable();
            $table->unsignedBigInteger('created_audi_id')->nullable();
    
            $table->unsignedBigInteger('product_station_id')->nullable();

            $table->integer('quantity')->default(0)->nullable();
            $table->json('metadata')->nullable();
            $table->boolean('active')->default(false);
            $table->softDeletes();


            $table->foreign('station_id')
                ->references('id')
                ->on('stations')
                ->onDelete('cascade');

            $table->foreign('product_station_id')
                ->references('id')
                ->on('product_stations')
                ->onDelete('cascade');

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
        Schema::dropIfExists('product_stations');
    }
}
