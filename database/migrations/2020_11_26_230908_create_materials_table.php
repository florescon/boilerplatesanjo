<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('part_number')->nullable();
            $table->string('name')->nullable();
            $table->longText('description')->nullable();
            $table->double('acquisition_cost')->nullable();
            $table->double('price')->nullable();
            $table->double('stock')->nullable();
            $table->unsignedSmallInteger('unit_id')->nullable();
            $table->unsignedMediumInteger('color_id')->nullable();
            $table->unsignedSmallInteger('size_id')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('materials');
    }
}
