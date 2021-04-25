<?php

namespace Database\Seeders;

use App\Models\Line;
use Illuminate\Database\Seeder;
use Database\Seeders\Traits\DisableForeignKeys;

class LineSeeder extends Seeder
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
            Line::factory()->times(100)->create();
        }

        $this->enableForeignKeys();

    }
}
