<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductionReceivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('production_receives', function (Blueprint $table) {
            $table->id();
            $table->integer('quantity')->default(0); // Campo para guardar la cantidad
            $table->date('received_at')->nullable(); // Fecha de recepción
            $table->unsignedBigInteger('receivable_id'); // ID del modelo relacionado
            $table->string('receivable_type'); // Clase del modelo relacionado
            $table->unsignedBigInteger('audi_id')->nullable();
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
        Schema::dropIfExists('production_receives');
    }
}
