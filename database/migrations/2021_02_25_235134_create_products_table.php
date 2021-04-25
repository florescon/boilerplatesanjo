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
            $table->double('price')->nullable();
            $table->double('stock')->default(0);
            $table->double('stock_revision')->default(0);
            $table->double('stock_store')->default(0);
            $table->string('file_name')->nullable();
            $table->longText('description')->nullable();
            $table->unsignedInteger('color_id')->nullable();
            $table->unsignedInteger('size_id')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->tinyInteger('sort')->default(1);
            $table->boolean('type')->default(1);
            $table->boolean('status')->default(1);
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
