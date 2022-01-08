<?php

use App\Domains\Auth\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepartamentsInformation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('departaments', function (Blueprint $table) {
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('rfc')->nullable();
            $table->enum('type_price', [User::PRICE_RETAIL, User::PRICE_AVERAGE_WHOLESALE, User::PRICE_WHOLESALE])->default(User::PRICE_RETAIL);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('departaments_information');
    }
}
