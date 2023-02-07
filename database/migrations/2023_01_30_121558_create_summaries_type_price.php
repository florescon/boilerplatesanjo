<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Domains\Auth\Models\User;

class CreateSummariesTypePrice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('summaries', function (Blueprint $table) {
            $table->enum('type_price', [User::PRICE_RETAIL, User::PRICE_AVERAGE_WHOLESALE, User::PRICE_WHOLESALE, User::PRICE_SPECIAL])->default(User::PRICE_RETAIL);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('summaries_type_price');
    }
}
