<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('slug')->unique()->nullable();
            $table->string('code',100)->unique()->nullable();
            $table->decimal('price', 8, 2)->nullable();
            $table->decimal('average_wholesale_price', 8, 2)->nullable();
            $table->decimal('wholesale_price', 8, 2)->nullable();
            $table->double('stock')->default(0);
            $table->double('stock_revision')->default(0);
            $table->double('stock_store')->default(0);
            $table->string('file_name')->nullable();
            $table->longText('description')->nullable();
            $table->unsignedBigInteger('color_id')->nullable();
            $table->unsignedBigInteger('size_id')->nullable();
            $table->unsignedBigInteger('line_id')->nullable();
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->double('discount')->default(0);
            $table->tinyInteger('sort')->default(1);
            $table->boolean('automatic_code')->default(true);
            $table->boolean('type')->default(true)->comment('Product: true, Service: false');
            $table->boolean('status')->default(true);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('parent_id')
                ->references('id')
                ->on('products')
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
        Schema::dropIfExists('products');
    }
}
