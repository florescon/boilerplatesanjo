<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsPrices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->integer('retail_price_percentage')->default(0);
            $table->integer('average_wholesale_price_percentage')->default(0);
            $table->integer('wholesale_price_percentage')->default(0);
            $table->integer('iva')->default(0);
            $table->boolean('round')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings_prices');
    }
}
