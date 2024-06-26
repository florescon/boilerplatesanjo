<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentThreadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_threads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('document_id')->nullable();
            $table->unsignedBigInteger('thread_id')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('document_id')
                ->references('id')
                ->on('documents')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('document_threads');
    }
}
