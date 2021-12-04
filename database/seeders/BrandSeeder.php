<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Database\Seeders\Traits\DisableForeignKeys;

class BrandSeeder extends Seeder
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
            Brand::factory()->times(100)->create();
        }

        $this->enableForeignKeys();
    }
}
