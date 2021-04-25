<?php

namespace Database\Seeders;

use App\Models\Size;
use Illuminate\Database\Seeder;
use Database\Seeders\Traits\DisableForeignKeys;

class SizeSeeder extends Seeder
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
            Size::factory()->times(100)->create();
        }

        $this->enableForeignKeys();

    }
}
