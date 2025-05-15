<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductionBatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('production_batches', function (Blueprint $table) {
            $table->id();
            $table->integer('folio');
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->unsignedBigInteger('status_id')->nullable();
            $table->unsignedBigInteger('personal_id')->nullable();
            $table->boolean('is_batch')->default(true);
            $table->boolean('is_supplier')->default(false);
            $table->boolean('is_principal')->default(false);
            $table->unsignedBigInteger('with_previous')->nullable();
            // $table->string('status')->default('pending'); // pending, in_progress, completed, cancelled
            $table->boolean('consumption')->default(false);
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('audi_id')->nullable();
            $table->date('date_entered')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('production_batches');
    }
}
