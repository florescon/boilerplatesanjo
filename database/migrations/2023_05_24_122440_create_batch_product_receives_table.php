<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBatchProductReceivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('batch_product_receives', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('batch_product_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->integer('quantity')->default(0)->nullable();
            $table->longText('comment')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->dateTime('approved')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            
            $table->foreign('batch_product_id')
                ->references('id')
                ->on('batch_products')
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
        Schema::dropIfExists('batch_product_receives');
    }
}
