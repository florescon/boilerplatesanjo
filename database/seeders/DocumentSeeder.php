<?php

namespace Database\Seeders;

use App\Models\Document;
use Illuminate\Database\Seeder;
use Database\Seeders\Traits\DisableForeignKeys;

class DocumentSeeder extends Seeder
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
            Document::factory()->times(20)->create();
        }

        $this->enableForeignKeys();
    }
}
