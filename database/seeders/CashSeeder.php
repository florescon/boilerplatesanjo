<?php

namespace Database\Seeders;

use App\Models\Cash;
use Illuminate\Database\Seeder;
use Database\Seeders\Traits\DisableForeignKeys;

class CashSeeder extends Seeder
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
            Cash::factory()->times(100)->create();
        }

        $this->enableForeignKeys();
    }
}
