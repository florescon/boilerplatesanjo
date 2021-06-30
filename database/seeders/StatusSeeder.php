<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\Traits\DisableForeignKeys;
use App\Models\Status;

class StatusSeeder extends Seeder
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

        Status::create([
            'id' => 1,
            'name' => 'Inicio de orden',
            'description' => 'Inicio de orden',
            'level' => -1,
            'percentage' => 5,
        ]);

        Status::create([
            'id' => 2,
            'name' => 'Final de orden',
            'description' => 'Final de orden',
            'level' => 20,
            'percentage' => 100,
        ]);

        Status::create([
            'id' => 3,
            'name' => 'Producción',
            'description' => 'Producción',
            'level' => 0,
            'percentage' => 10,
        ]);

        Status::create([
            'id' => 4,
            'name' => 'Corte',
            'description' => 'Corte de tela y tallas',
            'level' => 1,
            'percentage' => 30,
            'to_add_users' => true,
        ]);

        Status::create([
            'id' => 5,
            'name' => 'Sublimación full print',
            'description' => 'Sublimación para refilado',
            'level' => 2,
            'percentage' => 40,
        ]);

        Status::create([
            'id' => 6,
            'name' => 'Confección',
            'description' => 'Maquila de costura',
            'level' => 3,
            'percentage' => 50,
            'to_add_users' => true,
        ]);

        Status::create([
            'id' => 7,
            'name' => 'Almacén revisión intermedia',
            'description' => 'Almacén revisión intermedia',
            'level' => 10,
            'percentage' => 60,
        ]);

        Status::create([
            'id' => 8,
            'name' => 'Personalización de producto',
            'description' => 'Personalización, bordado, serigrafia,sublimacion o recorte de vinil',
            'level' => 11,
            'percentage' => 80,
            'to_add_users' => true,
        ]);

        Status::create([
            'id' => 9,
            'name' => 'Revisión final',
            'description' => 'Revisión de final mercancia y empaque para entrega',
            'level' => 19,
            'percentage' => 90,
        ]);

        $this->enableForeignKeys();

    }
}
