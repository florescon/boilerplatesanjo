<?php

namespace Database\Seeders;

use App\Models\Departament;
use Illuminate\Database\Seeder;
use Database\Seeders\Traits\DisableForeignKeys;

class DepartamentSeeder extends Seeder
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
            Departament::factory()->times(100)->create();
        }

        $this->enableForeignKeys();

    }
}
