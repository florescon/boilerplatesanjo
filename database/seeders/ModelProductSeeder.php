<?php

namespace Database\Seeders;

use App\Models\ModelProduct;
use Illuminate\Database\Seeder;
use Database\Seeders\Traits\DisableForeignKeys;

class ModelProductSeeder extends Seeder
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
            ModelProduct::factory()->times(100)->create();
        }

        $this->enableForeignKeys();

    }
}
