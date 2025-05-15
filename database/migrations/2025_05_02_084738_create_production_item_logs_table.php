<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductionItemLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('production_item_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_item_id')->constrained('production_batch_items')->onDelete('cascade');
            $table->foreignId('station_log_id')->constrained('production_station_logs')->onDelete('cascade');
            $table->integer('input_quantity')->default(0);
            $table->integer('output_quantity')->default(0);
            $table->integer('active')->default(0);
            $table->string('status'); // pending, in_progress, completed, cancelled
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['batch_item_id', 'station_log_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('production_item_logs');
    }
}
