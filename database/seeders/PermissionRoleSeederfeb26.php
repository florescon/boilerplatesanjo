<?php

namespace Database\Seeders;

use App\Domains\Auth\Models\Permission;
use App\Domains\Auth\Models\Role;
use App\Domains\Auth\Models\User;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;

class PermissionRoleSeederfeb26 extends Seeder
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

        $user = Permission::where('name', 'admin.access.user')->first();

        $user->children()->saveMany([
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.user.exportcustomer',
                'description' => 'Exportar clientes',
            ]),
        ]);

        $departament = Permission::where('name', 'admin.access.departament')->first();

        $departament->children()->saveMany([
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.departament.exportdepartament',
                'description' => 'Exportar departamentos',
            ]),
        ]);

        $order = Permission::where('name', 'admin.access.order')->first();

        $order->children()->saveMany([
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.order.quotation',
                'description' => 'Crear cotizaciones',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.order.print_service_order',
                'description' => 'Imprimir órdenes de servicio',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.order.create_service_order',
                'description' => 'Crear órdenes de servicio',
            ]),
        ]);

        $product = Permission::where('name', 'admin.access.product')->first();

        $product->children()->saveMany([
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.product.special-price',
                'description' => 'Ver precios especiales',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.product.kardex',
                'description' => 'Ver Kardex',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.product.delete-attributes',
                'description' => 'Eliminar atributos',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.product.move-stocks',
                'description' => 'Mover entre stocks',
            ]),
        ]);

        $store = Permission::where('name', 'admin.access.store')->first();

        $store->children()->saveMany([
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.store.order.modify_store',
                'description' => 'Modificar pedidos/ventas tienda',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.store.order.print',
                'description' => 'Imprimir tickets tienda',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.store.order.records_payment',
                'description' => 'Ver pagos de tienda',
            ]),
        ]);

        $this->enableForeignKeys();
    }
}
