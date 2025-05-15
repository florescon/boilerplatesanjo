<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProductionBatchIdToMaterialOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('material_orders', function (Blueprint $table) {
            // Añadir production_batch_id después de station_id
            $table->unsignedBigInteger('production_batch_id')->nullable()->after('station_id');

            // Clave foránea
            $table->foreign('production_batch_id')
                ->references('id')
                ->on('production_batches')
                ->onDelete('set null'); // O 'cascade' si prefieres
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('material_orders', function (Blueprint $table) {
            $table->dropForeign(['production_batch_id']);
            $table->dropColumn('production_batch_id');
        });
    }
}
