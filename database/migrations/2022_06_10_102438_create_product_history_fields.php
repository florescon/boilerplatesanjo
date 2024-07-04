<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductHistoryFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_histories', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->nullable();
            $table->double('old_stock', 15, 8)->nullable();
            $table->double('stock', 15, 8)->nullable();
            $table->decimal('price', 8, 2)->default(0)->nullable();
            $table->string('old_type_stock')->nullable();
            $table->string('type_stock')->nullable();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->boolean('is_output')->default(false);
            $table->unsignedBigInteger('audi_id')->nullable();
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
        Schema::table('product_histories', function (Blueprint $table) {

        });
    }
}
