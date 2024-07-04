<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartFeedstocksChangeName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cart_feedstocks', function (Blueprint $table) {
            $table->renameColumn('quantity', 'quantity_old');
            $table->renameColumn('quantity_', 'quantity');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cart_feedstocks', function (Blueprint $table) {

        });
    }
}
