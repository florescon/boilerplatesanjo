<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBatchObservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('batch_observations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('batch_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->enum('observation_type', [
                'defect',
                'incident',
                'corrective_action',
                'recommendation'
            ]);
            
            $table->text('description');

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
        Schema::dropIfExists('batch_observations');
    }
}
