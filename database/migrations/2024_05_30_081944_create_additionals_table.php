<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdditionalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('additionals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('material_id')->nullable();
            $table->integer('quantity')->default(1);
            $table->decimal('price', 8, 2)->nullable();
            $table->decimal('price_without_tax', 8, 2)->nullable();
            $table->longText('comment')->nullable();
            $table->string('type')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedSmallInteger('branch_id')->default(0);
            $table->date('date_entered')->nullable();
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
        Schema::dropIfExists('additionals');
    }
}
