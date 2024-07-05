<?php

namespace Database\Seeders;

use App\Domains\Auth\Models\Permission;
use Database\Seeders\Traits\DisableForeignKeys;
use App\Domains\Auth\Models\User;
use Illuminate\Database\Seeder;

class PermissionRoleSeederjul0724 extends Seeder
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

        $dashboard = Permission::where('name', 'admin.access.dashboard')->first();

        $dashboard->children()->saveMany([
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.dashboard.chart',
                'description' => 'Visualizar panel de gráficos',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.dashboard.information',
                'description' => 'Visualizar panel de información',
            ]),
        ]);

        $station = Permission::create([
            'type' => User::TYPE_ADMIN,
            'name' => 'admin.access.station',
            'description' => 'Todos los permisos de Estaciones',
        ]);

        $station->children()->saveMany([
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.station.list',
                'description' => 'Ver Estaciones',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.station.create',
                'description' => 'Crear Estaciones',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.station.modify',
                'description' => 'Modificar Estaciones',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.station.export',
                'description' => 'Exportar Estaciones',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.station.delete',
                'description' => 'Eliminar Estaciones',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.station.deleted',
                'description' => 'Ver Estaciones eliminadas',
            ]),
        ]);

        $bom = Permission::create([
            'type' => User::TYPE_ADMIN,
            'name' => 'admin.access.bom',
            'description' => 'Todos los permisos de Explosión de Materiales',
        ]);

        $bom->children()->saveMany([
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.bom.show',
                'description' => 'Visualizar Explosión de Materiales',
            ]),
        ]);

        $thread = Permission::create([
            'type' => User::TYPE_ADMIN,
            'name' => 'admin.access.thread',
            'description' => 'Todos los permisos de Hilos',
        ]);

        $thread->children()->saveMany([
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.thread.list',
                'description' => 'Ver Hilos',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.thread.create',
                'description' => 'Crear Hilos',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.thread.modify',
                'description' => 'Modificar Hilos',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.thread.export',
                'description' => 'Exportar Hilos',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.thread.delete',
                'description' => 'Eliminar Hilos',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.thread.deleted',
                'description' => 'Ver Hilos eliminados',
            ]),
        ]);


        $family = Permission::create([
            'type' => User::TYPE_ADMIN,
            'name' => 'admin.access.family',
            'description' => 'Todos los permisos de Familias',
        ]);

        $family->children()->saveMany([
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.family.list',
                'description' => 'Ver Familias',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.family.create',
                'description' => 'Crear Familias',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.family.modify',
                'description' => 'Modificar Familias',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.family.export',
                'description' => 'Exportar Familias',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.family.delete',
                'description' => 'Eliminar Familias',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.family.deleted',
                'description' => 'Ver Familias eliminadas',
            ]),
        ]);


        $typeService = Permission::create([
            'type' => User::TYPE_ADMIN,
            'name' => 'admin.access.typeservice',
            'description' => 'Todos los permisos de Tipo de Servicio',
        ]);

        $typeService->children()->saveMany([
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.typeservice.list',
                'description' => 'Ver Tipo de Servicio',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.typeservice.create',
                'description' => 'Crear Tipo de Servicio',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.typeservice.modify',
                'description' => 'Modificar Tipo de Servicio',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.typeservice.export',
                'description' => 'Exportar Tipo de Servicio',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.typeservice.delete',
                'description' => 'Eliminar Tipo de Servicio',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.typeservice.deleted',
                'description' => 'Ver Tipos de Servicios eliminados',
            ]),
        ]);

        $imageService = Permission::create([
            'type' => User::TYPE_ADMIN,
            'name' => 'admin.access.imageservice',
            'description' => 'Todos los permisos de Imagen de Servicio',
        ]);

        $imageService->children()->saveMany([
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.imageservice.list',
                'description' => 'Ver Imagen de Servicio',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.imageservice.create',
                'description' => 'Crear Imagen de Servicio',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.imageservice.modify',
                'description' => 'Modificar Imagen de Servicio',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.imageservice.export',
                'description' => 'Exportar Imagen de Servicio',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.imageservice.delete',
                'description' => 'Eliminar Imagen de Servicio',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.imageservice.deleted',
                'description' => 'Ver Imagenes de Servicios eliminados',
            ]),
        ]);

        $inventory = Permission::create([
            'type' => User::TYPE_ADMIN,
            'name' => 'admin.access.inventory',
            'description' => 'Todos los permisos de Inventarios',
        ]);

        $inventory->children()->saveMany([
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.inventory.production',
                'description' => 'Crear Inventario Principal',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.inventory.store',
                'description' => 'Crear Inventario Tienda',
            ]),
        ]);


        $vendor = Permission::create([
            'type' => User::TYPE_ADMIN,
            'name' => 'admin.access.vendor',
            'description' => 'Todos los permisos de Proveedores',
        ]);

        $vendor->children()->saveMany([
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.vendor.list',
                'description' => 'Ver Proveedor',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.vendor.create',
                'description' => 'Crear Proveedor',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.vendor.modify',
                'description' => 'Modificar Proveedor',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.vendor.export',
                'description' => 'Exportar Proveedor',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.vendor.delete',
                'description' => 'Eliminar Proveedor',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.vendor.deleted',
                'description' => 'Ver Proveedores eliminados',
            ]),
        ]);

        $this->enableForeignKeys();
    }
}
