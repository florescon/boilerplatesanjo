<?php

namespace Database\Seeders;

use App\Models\Finance;
use Illuminate\Database\Seeder;
use Database\Seeders\Traits\DisableForeignKeys;

class FinanceSeeder extends Seeder
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
            Finance::factory()->times(50)->create();
        }

        $this->enableForeignKeys();
    }
}
