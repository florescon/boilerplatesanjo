<?php

namespace Database\Seeders;

use App\Models\Material;
use Illuminate\Database\Seeder;
use Database\Seeders\Traits\DisableForeignKeys;

class MaterialSeeder extends Seeder
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
            Material::factory()->times(1000)->create();
        }

        $this->enableForeignKeys();
    }
}
