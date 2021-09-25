<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;
use Database\Seeders\Traits\DisableForeignKeys;

class PageSeeder extends Seeder
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
            Page::factory()->times(100)->create();
        }

        $this->enableForeignKeys();

    }
}
