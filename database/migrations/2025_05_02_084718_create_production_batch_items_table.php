<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductionBatchItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('production_batch_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->constrained('production_batches')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->unsignedBigInteger('status_id')->nullable();
            // $table->foreignId('product_order_id')->constrained('product_order')->onDelete('cascade');
            $table->integer('input_quantity')->default(0);
            $table->integer('output_quantity')->default(0);
            $table->integer('active')->default(0);
            $table->boolean('is_principal')->default(false);
            $table->unsignedBigInteger('with_previous')->nullable();
            $table->string('status')->default('pending'); // pending, in_progress, completed, cancelled
            $table->string('current_station')->default('recepcion'); // recepcion, corte, ensamble, etc.
            $table->timestamps();
            
            $table->index(['batch_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('production_batch_items');
    }
}
