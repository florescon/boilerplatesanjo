<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStationPreconsumptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('station_preconsumptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('station_id')->nullable();
            $table->unsignedBigInteger('material_id')->nullable();
            $table->double('quantity', 15, 8)->default(0)->nullable();
            $table->timestamps();

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
        Schema::dropIfExists('station_preconsumptions');
    }
}
