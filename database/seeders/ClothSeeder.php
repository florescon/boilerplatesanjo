<?php

namespace Database\Seeders;

use App\Models\Cloth;
use Illuminate\Database\Seeder;
use Database\Seeders\Traits\DisableForeignKeys;

class ClothSeeder extends Seeder
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
            Cloth::factory()->times(100)->create();
        }

        $this->enableForeignKeys();

    }
}
