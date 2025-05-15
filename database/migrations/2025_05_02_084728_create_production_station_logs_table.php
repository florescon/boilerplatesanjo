<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductionStationLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('production_station_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->constrained('production_batches')->onDelete('cascade');
            $table->string('station_name');
            $table->unsignedBigInteger('status_id')->nullable();
            $table->unsignedBigInteger('personal_id')->nullable();
            $table->timestamp('started_at')->useCurrent();
            $table->timestamp('completed_at')->nullable();
            $table->string('status')->default('in_progress'); // pending, in_progress, completed, cancelled
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('audi_id')->nullable();
            $table->timestamps();

            
            $table->index(['batch_id', 'station_name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('production_station_logs');
    }
}
