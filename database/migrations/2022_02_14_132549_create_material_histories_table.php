<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaterialHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('material_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('material_id')->nullable();
            $table->double('old_stock', 15, 8)->nullable();
            $table->double('stock', 15, 8)->nullable();
            $table->decimal('old_price', 8, 2)->default(0)->nullable();
            $table->decimal('price', 8, 2)->default(0)->nullable();
            $table->unsignedBigInteger('audi_id')->nullable();
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
        Schema::dropIfExists('material_histories');
    }
}
