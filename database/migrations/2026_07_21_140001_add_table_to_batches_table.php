<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTableToBatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

            Schema::dropIfExists('batch_product_receives');
            Schema::dropIfExists('batch_products');
            Schema::dropIfExists('batch_items');
            Schema::dropIfExists('batches');

        Schema::enableForeignKeyConstraints();

        Schema::create('batches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->nullable();

            $table->foreignId('process_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('status_name', 50)->nullable();
            $table->timestamp('started_at')->useCurrent();
            $table->timestamp('finished_at')->nullable();
            $table->decimal('hours', 6, 2)->default(0);

            $table->decimal('produced_quantity', 12, 2)
                ->default(0)
                ->comment('Cantidad producida');

            $table->decimal('defective_quantity', 12, 2)
                ->default(0)
                ->comment('Cantidad defectuosa');

            $table->decimal('approved_quantity', 12, 2)
                ->default(0)
                ->comment('Cantidad aprobada');

            $table->decimal('production_time_hours', 6, 2)
                ->default(0)
                ->comment('Tiempo producción en horas');

            $table->decimal('weighted_consumption', 12, 4)
                ->default(0)
                ->comment('Consumo ponderado');

            // Costos
            $table->decimal('raw_material_cost', 12, 2)
                ->default(0)
                ->comment('Costo materia prima');

            $table->decimal('labor_cost', 12, 2)
                ->default(0)
                ->comment('Costo mano de obra');

            $table->decimal('out_sourcing_cost', 12, 2)
                ->default(0)
                ->comment('Costo maquila');

            $table->decimal('overhead_cost', 12, 2)
                ->default(0)
                ->comment('Costo indirecto');

            $table->decimal('total_cost', 12, 2)
                ->default(0)
                ->comment('Costo total');

            $table->decimal('unit_cost', 12, 4)
                ->default(0)
                ->comment('Costo unitario');

            // Venta y rentabilidad
            $table->decimal('unit_sale_price', 12, 2)
                ->default(0)
                ->comment('Precio unitario');

            $table->decimal('tax_rate', 5, 2)
                ->default(0)
                ->comment('IVA porcentaje');

            $table->decimal('profit_margin', 5, 2)
                ->default(0)
                ->comment('Margen de ganancia porcentaje');

            $table->decimal('total_batch_value', 12, 2)
                ->default(0)
                ->comment('Valor total del lote');

            $table->decimal('total_profit', 12, 2)
                ->default(0)
                ->comment('Utilidad total');

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
        Schema::table('batches', function (Blueprint $table) {
            //
        });
    }
}
