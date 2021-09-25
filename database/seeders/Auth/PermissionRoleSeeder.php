<?php

namespace Database\Seeders\Auth;

use App\Domains\Auth\Models\Permission;
use App\Domains\Auth\Models\Role;
use App\Domains\Auth\Models\User;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;

/**
 * Class PermissionRoleTableSeeder.
 */
class PermissionRoleSeeder extends Seeder
{
    use DisableForeignKeys;

    /**
     * Run the database seed.
     */
    public function run()
    {
        $this->disableForeignKeys();

        if (app()->environment() !== 'production') {

        // Create Roles

        // Non Grouped Permissions
        //

        // Grouped permissions
        // Users category
        $users = Permission::create([
            'type' => User::TYPE_ADMIN,
            'name' => 'admin.access.user',
            'description' => 'Todos los permisos de usuario',
        ]);


        $users->children()->saveMany([
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.user.list',
                'description' => 'Ver usuarios',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.user.deactivate',
                'description' => 'Desactivar usuarios',
                'sort' => 2,
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.user.reactivate',
                'description' => 'Reactivar usuarios',
                'sort' => 3,
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.user.clear-session',
                'description' => 'Borrar sesiones de usuario',
                'sort' => 4,
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.user.impersonate',
                'description' => 'Personificar usuarios',
                'sort' => 5,
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.user.change-password',
                'description' => 'Cambiar contraseña de usuarios',
                'sort' => 6,
            ]),
        ]);

        $material = Permission::create([
            'type' => User::TYPE_ADMIN,
            'name' => 'admin.access.material',
            'description' => 'Todos los permisos de materia prima',
        ]);


        $material->children()->saveMany([
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.material.list',
                'description' => 'Ver materia prima',
            ]),
        ]);


        $color = Permission::create([
            'type' => User::TYPE_ADMIN,
            'name' => 'admin.access.color',
            'description' => 'Todos los permisos de colores',
        ]);


        $color->children()->saveMany([
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.color.list',
                'description' => 'Ver colores',
            ]),
        ]);


        $material->children()->saveMany([
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.material.show-quantities',
                'description' => 'Ver cantidades',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.material.modify-quantities',
                'description' => 'Modificar cantidades',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.material.show-prices',
                'description' => 'Ver precios',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.material.create',
                'description' => 'Crear materia prima',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.material.modify',
                'description' => 'Modificar materia prima',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.material.export',
                'description' => 'Exportar materia prima',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.material.delete',
                'description' => 'Borrar materia prima',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.material.deleted',
                'description' => 'Ver materia prima eliminada',
            ]),
        ]);


        $color->children()->saveMany([
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.color.list',
                'description' => 'Crear colores',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.color.modify',
                'description' => 'Modificar colores',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.color.modify',
                'description' => 'Exportar colores',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.color.delete',
                'description' => 'Eliminar colores',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.color.deleted',
                'description' => 'Ver colores eliminados',
            ]),
        ]);


        $size = Permission::create([
            'type' => User::TYPE_ADMIN,
            'name' => 'admin.access.size',
            'description' => 'Todos los permisos de tallas',
        ]);
        $size->children()->saveMany([
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.size.list',
                'description' => 'Ver tallas',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.size.list',
                'description' => 'Crear tallas',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.size.modify',
                'description' => 'Modificar tallas',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.size.modify',
                'description' => 'Exportar tallas',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.size.delete',
                'description' => 'Eliminar tallas',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.size.deleted',
                'description' => 'Ver tallas eliminados',
            ]),
        ]);


        $cloth = Permission::create([
            'type' => User::TYPE_ADMIN,
            'name' => 'admin.access.cloth',
            'description' => 'Todos los permisos de telas',
        ]);
        $cloth->children()->saveMany([
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.cloth.list',
                'description' => 'Ver telas',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.cloth.list',
                'description' => 'Crear telas',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.cloth.modify',
                'description' => 'Modificar telas',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.cloth.modify',
                'description' => 'Exportar telas',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.cloth.delete',
                'description' => 'Eliminar telas',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.cloth.deleted',
                'description' => 'Ver telas eliminados',
            ]),
        ]);


        $line = Permission::create([
            'type' => User::TYPE_ADMIN,
            'name' => 'admin.access.line',
            'description' => 'Todos los permisos de lineas',
        ]);
        $line->children()->saveMany([
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.line.list',
                'description' => 'Ver lineas',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.line.list',
                'description' => 'Crear lineas',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.line.modify',
                'description' => 'Modificar lineas',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.line.modify',
                'description' => 'Exportar lineas',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.line.delete',
                'description' => 'Eliminar lineas',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.line.deleted',
                'description' => 'Ver lineas eliminados',
            ]),
        ]);


        $unit = Permission::create([
            'type' => User::TYPE_ADMIN,
            'name' => 'admin.access.unit',
            'description' => 'Todos los permisos de lineas',
        ]);
        $unit->children()->saveMany([
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.unit.list',
                'description' => 'Ver lineas',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.unit.list',
                'description' => 'Crear lineas',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.unit.modify',
                'description' => 'Modificar lineas',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.unit.modify',
                'description' => 'Exportar lineas',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.unit.delete',
                'description' => 'Eliminar lineas',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.unit.deleted',
                'description' => 'Ver lineas eliminados',
            ]),
        ]);



        $product = Permission::create([
            'type' => User::TYPE_ADMIN,
            'name' => 'admin.access.product',
            'description' => 'Todos los permisos de productos',
        ]);
        $product->children()->saveMany([
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.product.list',
                'description' => 'Ver productos',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.product.show-quantities',
                'description' => 'Ver cantidades',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.product.modify-quantities',
                'description' => 'Modificar cantidades',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.product.show-prices',
                'description' => 'Ver precios',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.product.modify-prices-codes',
                'description' => 'Modificar precios y codigos',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.product.create',
                'description' => 'Crear productos',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.product.modify',
                'description' => 'Modificar productos',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.product.export',
                'description' => 'Exportar productos',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.product.delete',
                'description' => 'Borrar productos',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.product.deleted',
                'description' => 'Ver productos eliminada',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.product.consumption',
                'description' => 'Ver consumo',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.product.modify-consumption',
                'description' => 'Modificar consumos',
            ]),
        ]);


        $order = Permission::create([
            'type' => User::TYPE_ADMIN,
            'name' => 'admin.access.order',
            'description' => 'Todos los permisos de ordenes',
        ]);
        $order->children()->saveMany([
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.order.order',
                'description' => 'Ver ordenes',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.order.sales',
                'description' => 'Ver ventas',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.order.order-sales',
                'description' => 'Ver ventas/ventas',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.order.suborders',
                'description' => 'Ver subordenes',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.order.list',
                'description' => 'Crear ordenes',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.order.modify',
                'description' => 'Modificar ordenes',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.order.modify',
                'description' => 'Exportar ordenes',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.order.delete',
                'description' => 'Eliminar ordenes',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.order.deleted',
                'description' => 'Ver ordenes eliminados',
            ]),
        ]);

        }

        // Assign Permissions to other Roles
        //

        $this->enableForeignKeys();
    }
}
