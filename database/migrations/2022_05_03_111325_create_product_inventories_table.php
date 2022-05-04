<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_inventories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->double('capture', 15, 8)->default(0)->nullable();
            $table->double('stock', 15, 8)->default(0)->nullable();
            $table->unsignedBigInteger('audi_id')->nullable();
            $table->string('type')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->longText('comment')->nullable();
            $table->unsignedBigInteger('inventory_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_inventories');
    }
}
