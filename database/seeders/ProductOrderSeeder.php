<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductOrder;
use Database\Seeders\Traits\DisableForeignKeys;

class ProductOrderSeeder extends Seeder
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
            ProductOrder::factory()->times(10000)->create();
        }

        $this->enableForeignKeys();
    }
}
