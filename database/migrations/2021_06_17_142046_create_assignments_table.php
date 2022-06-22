<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('order_id')->nullable();
            $table->unsignedBigInteger('ticket_id')->nullable();
            $table->unsignedInteger('status_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->integer('quantity')->default(0)->nullable();
            $table->integer('assignmentable_id')->unsigned();
            $table->string('assignmentable_type');
            $table->boolean('output')->default(false);
            $table->timestamps();

            $table->foreign('ticket_id')
                ->references('id')
                ->on('tickets')
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
        Schema::dropIfExists('assignments');
    }
}
