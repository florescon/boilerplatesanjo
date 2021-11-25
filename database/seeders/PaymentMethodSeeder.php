<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;
use Database\Seeders\Traits\DisableForeignKeys;

class PaymentMethodSeeder extends Seeder
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

        PaymentMethod::create([
            'id' => 1,
            'title' => 'Efectivo',
            'short_title' => 'Efectivo',
            'description' => 'Pago realizado con dinero directamente en el establecimiento',
            'is_enabled' => true,
        ]);

        PaymentMethod::create([
            'id' => 2,
            'title' => 'Tarjeta de Débito',
            'short_title' => 'T. Débito',
            'description' => 'Éste siempre deberá tener fondos',
            'is_enabled' => true,
        ]);

        PaymentMethod::create([
            'id' => 3,
            'title' => 'Tarjeta de Crédito',
            'short_title' => 'T. Crédito',
            'description' => 'Autorizada por la entidad financiera para una tasa de intereses puntual y sus respectivos cobros asociados',
            'is_enabled' => true,
        ]);

        PaymentMethod::create([
            'id' => 4,
            'title' => 'Cheque Nominativo',
            'short_title' => 'Cheque',
            'description' => 'Se emite un cheque a nombre del beneficiado',
            'is_enabled' => true,
        ]);

        PaymentMethod::create([
            'id' => 5,
            'title' => 'Transferencia Electrónica',
            'short_title' => 'Transferencia',
            'description' => 'Transferencia electrónica entre bancos sin importar si son cuentahabiente de la misma institución',
            'is_enabled' => true,
        ]);

        PaymentMethod::create([
            'id' => 6,
            'title' => 'Monedero Electrónico',
            'short_title' => 'Monedero E.',
            'description' => 'Emitido por un monedero autorizado',
            'is_enabled' => true,
        ]);

        PaymentMethod::create([
            'id' => 7,
            'title' => 'Vales de Despensa',
            'short_title' => 'Vale de D.',
            'description' => 'Vales que el contribuyente es acreedor',
            'is_enabled' => true,
        ]);

        $this->enableForeignKeys();
    }
}
