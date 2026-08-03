<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBatchOperationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('batch_operations', function (Blueprint $table) {
            $table->id();


            // Orden de producción (opcional)
            $table->foreignId('order_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // Lote
            $table->foreignId('batch_id')
                ->constrained()
                ->cascadeOnDelete();

            // Producto dentro del lote
            $table->foreignId('batch_item_id')
                ->constrained()
                ->cascadeOnDelete();

            // Operación del proceso
            $table->foreignId('operation_id')
                ->constrained()
                ->cascadeOnDelete();

            // Copia histórica del nombre de la operación
            $table->string('operation_name', 50);

            // Orden de ejecución
            $table->unsignedSmallInteger('sequence');

            //Esperado
            $table->unsignedSmallInteger('expected')->default(0)
                ->comment('Esperado');
            //Procesando
            $table->unsignedSmallInteger('processed')->default(0)
                ->comment('Procesando');

            //Recibiendo
            $table->unsignedSmallInteger('received')->default(0)
                ->comment('Recibiendo');
            //Entregado
            $table->unsignedSmallInteger('delivered')->default(0)
                ->comment('Entregado');


            // Estado de la operación
            $table->string('status_name', 50)->default('pending');

            // Tiempo empleado
            $table->decimal('hours', 8, 2)->nullable();

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
        Schema::dropIfExists('batch_operations');
    }
}
