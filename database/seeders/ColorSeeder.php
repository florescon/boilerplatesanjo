<?php

namespace Database\Seeders;

use App\Models\Color;
use Illuminate\Database\Seeder;
use Database\Seeders\Traits\DisableForeignKeys;

class ColorSeeder extends Seeder
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
            Color::factory()->times(1000)->create();
        }

        $this->enableForeignKeys();

    }
}
