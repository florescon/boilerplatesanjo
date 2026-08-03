<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBatchItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('batch_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('batch_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('product_order_id')
                ->constrained('product_order')
                ->cascadeOnDelete();
                
            $table->foreignId('product_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->unsignedSmallInteger('quantity');

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
        Schema::dropIfExists('batch_items');
    }
}
