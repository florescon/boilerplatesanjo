<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOutProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('out_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('out_id')->nullable();
            $table->unsignedBigInteger('material_id')->nullable();
            $table->integer('quantity')->default(1);
            $table->decimal('price', 8, 2)->nullable();
            $table->longText('comment')->nullable();
            $table->string('type')->nullable();
            $table->timestamps();

            $table->foreign('out_id')
                ->references('id')
                ->on('outs')
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
        Schema::dropIfExists('out_products');
    }
}
