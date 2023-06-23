<?php

namespace Database\Seeders;

use App\Domains\Auth\Models\Permission;
use Database\Seeders\Traits\DisableForeignKeys;
use App\Domains\Auth\Models\User;
use Illuminate\Database\Seeder;

class PermissionRoleSeederjun07 extends Seeder
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

        // Grouped permissions
        // Dashboard
        $dashboard = Permission::create([
            'type' => User::TYPE_ADMIN,
            'name' => 'admin.access.dashboard',
            'description' => 'Todos los permisos del tablero',
        ]);

        $dashboard->children()->saveMany([
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.dashboard.kanban',
                'description' => 'Visualizar tablero kanban',
            ]),
        ]);

        $this->enableForeignKeys();
    }
}
