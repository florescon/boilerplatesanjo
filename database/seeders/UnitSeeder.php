<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;
use Database\Seeders\Traits\DisableForeignKeys;

class UnitSeeder extends Seeder
{

    use DisableForeignKeys;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->disableForeignKeys();

        if (app()->environment() !== 'production') {
            Unit::factory()->times(100)->create();
        }

        $this->enableForeignKeys();

    }
}
