<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Batch;
use App\Models\BatchItem;
use App\Models\BatchOperation;
use App\Models\ProductOrder;
use App\Models\ProcessRoute;
use Illuminate\Support\Facades\DB;

class CatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $processes = [
        //     'Antiguo' => [
        //         'Captura',
        //         'Producto Terminado',
        //         'Produccion',
        //         'corte',
        //         'Sublimación full print',
        //         'confeccion',
        //         'Almacén revisión intermedia',
        //         'Personalización de producto',
        //         'Revisión final',
        //         'Conformado de pedidos',
        //         'Embarque',
        //         'Inspección de Calidad',
        //         'Pedido a Proveedor',
        //         'Salida',
        //     ],
        // ];

        // foreach ($processes as $processName => $operations) {

        // $process = Process::create([
        //         'name' => $processName,
        //         'is_active' => 1,
        //     ]);

        //     foreach ($operations as $sequence => $operationName) {

        //         $operation = Operation::firstOrCreate([
        //             'name' => $operationName,
        //         ]);

        //         ProcessRoute::create([
        //             'process_id'   => $process->id,
        //             'operation_id' => $operation->id,
        //             'sequence'     => $sequence + 1,
        //         ]);
        //     }
        // }






DB::transaction(function () {

    $orderId = 4074;
    $processId = 2;

    $batches = [
        [
            'items' => [
                [
                    'product_order_id' => 24669,
                    'product_id' => 4343,
                    'quantity' => 5,
                ],
                [
                    'product_order_id' => 24670,
                    'product_id' => 4343,
                    'quantity' => 5,
                ],
                [
                    'product_order_id' => 24671,
                    'product_id' => 4350,
                    'quantity' => 11,
                ],
                [
                    'product_order_id' => 24672,
                    'product_id' => 4344,
                    'quantity' => 33,
                ],
            ],
        ],
    ];

    $processRoutes = ProcessRoute::with('operation')
        ->where('process_id', $processId)
        ->orderBy('sequence')
        ->get();

    foreach ($batches as $batchData) {

        $batch = Batch::create([
            'order_id'   => $orderId,
            'process_id' => $processId,
            'status_name'     => 'pending',
        ]);

        foreach ($batchData['items'] as $item) {

            $batchItem = BatchItem::create([
                'batch_id'         => $batch->id,
                'product_order_id' => $item['product_order_id'],
                'product_id'       => $item['product_id'],
                'quantity'       => $item['quantity'],
            ]);

            foreach ($processRoutes as $route) {

                BatchOperation::create([
                    'order_id'       => $orderId,
                    'batch_id'       => $batch->id,
                    'batch_item_id'  => $batchItem->id,
                    'operation_id'   => $route->operation_id,
                    'operation_name' => $route->operation->name,
                    'sequence'       => $route->sequence,
                    'expected'       => $item['quantity'],
                    'status_name'    => 'pending',
                    'product_id'     => $item['product_id'],
                ]);
            }
        }
    }
});

    }
}
