<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('status_id')->nullable();
            $table->unsignedBigInteger('personal_id')->nullable();
            $table->longText('comment')->nullable();
            $table->date('date_entered')->nullable();
            $table->unsignedBigInteger('created_audi_id')->nullable();
            $table->unsignedBigInteger('last_modified_audi_id')->nullable();

            $table->unsignedBigInteger('station_id')->nullable();

            $table->boolean('active')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->onDelete('cascade');

            $table->foreign('station_id')
                ->references('id')
                ->on('stations')
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
        Schema::dropIfExists('stations');
    }
}
