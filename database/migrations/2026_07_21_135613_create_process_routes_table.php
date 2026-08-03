<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcessRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('process_routes', function (Blueprint $table) {

            $table->id();

            $table->foreignId('process_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('operation_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->unsignedSmallInteger('sequence');

            $table->timestamps();

            $table->unique([
                'process_id',
                'sequence'
            ]);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('process_routes');
    }
}
