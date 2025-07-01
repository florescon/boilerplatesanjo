<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductStation;
use App\Models\ProductionBatchItem;
use App\Models\ProductionBatchItemHistory;
use App\Models\ProductStationReceived;
use App\Models\Status;
use App\Models\Station;
use App\Models\Additional;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Domains\Auth\Models\User;
use DB;
use PDF;

class StatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.status.index');
    }

    public function showInformation(Status $status)
    {
        if($status->active != true){
            abort(401);
        }

        return view('backend.information.show', compact('status'));
    }

    public function assignments(Status $status)
    {
        return view('backend.status.assignments-status', compact('status'));
    }

    public function deleted()
    {
        return view('backend.status.deleted');
    }

    public function ticket_materia(Status $status)
    {
        if(($status->active != true) || !$status->initial_lot){
            abort(401);
        }

        $query = ProductStation::query()->with('product', 'status', 'station')
            ->where('status_id', $status->id)
            ->where('active', true)
            ->where('not_consider', false)
            ->whereHas('station', function ($query) {
                $query->where('consumption', false);
            })
            ->get();

        $consumptionCollect = collect();
        $ordercollection = collect();
        $productsCollection = collect();

            foreach($query as $product_statione){
                $quantity = $product_statione->quantity;

                $ordercollection->push([
                    'order_id' => $product_statione->order_id,
                    'station_id' => $product_statione->station_id,
                ]);

                $productsCollection->push([
                    'productId' => $product_statione->id,
                    'productParentId' => $product_statione->product->parent_id ?? $product_statione->product_id,
                    'productParentName' => $product_statione->product->only_name ?? null,
                    'productParentCode' => $product_statione->product->parent_code ?? null,
                    'productOrder' => $product_statione->order_id,
                    'productName' => $product_statione->product->full_name_clear ?? null,
                    'productColor' => $product_statione->product->color_id,
                    'productColorName' => $product_statione->product->color->name ?? '',
                    'productSizeName' => $product_statione->product->size->name ?? '',
                    'productQuantity' => $product_statione->quantity,
                    'isService' => !$product_statione->product->parent_id ? true : false,
                    'customer' => $product_statione->order->user_name ?? null,
                ]);

                if($product_statione->product_order->gettAllConsumptionSecond($quantity) != 'empty'){
                    foreach($product_statione->product_order->gettAllConsumptionSecond($quantity) as $key => $consumption){
                        $consumptionCollect->push([
                            'order' => $product_statione->order_id,
                            'product_order_id' => $product_statione->product_order->id, 
                            'material_name' => $consumption['material'],
                            'part_number' => $consumption['part_number'],
                            'material_id' => $key,
                            'unit' => $consumption['unit'],
                            'unit_measurement' => $consumption['unit_measurement'],
                            'vendor' => $consumption['vendor'],
                            'family' => $consumption['family'],
                            'quantity' => $consumption['quantity'],
                            'stock' => $consumption['stock'],
                        ]);
                    }
                }
            }

        $materials = $consumptionCollect->groupBy('material_id')->map(function ($row) {
                    return [
                        'order' => $row[0]['order'],
                        'product_order_id' => $row[0]['product_order_id'], 
                        'material_name' => $row[0]['material_name'],
                        'part_number' => $row[0]['part_number'],
                        'material_id' => $row[0]['material_id'],
                        'unit' => $row[0]['unit'],
                        'unit_measurement' => $row[0]['unit_measurement'],
                        'vendor' => $row[0]['vendor'],
                        'family' => $row[0]['family'],
                        'quantity' => $row->sum('quantity'),
                        'stock' => $row[0]['stock'],
                    ];
                });


        $allMaterials = $materials->map(function ($product) {
        return [
            'order'            => $product['order'],
            'material_name' => $product['material_name'],
            'part_number'         => $product['part_number'],
            'unit_measurement' => $product['unit_measurement'],
            'quantity' => $product['quantity'],
            ];
        });

        // dd($ordercollection->unique('order_id'));

        $ordercollectionn = $ordercollection->groupBy(['order_id', 'station_id']);

        // dd($ordercollectionn);

        $ordercollectionn->toArray();


        // dd($ordercollection);

        $pdf = PDF::loadView('backend.information.ticket-feedstock',compact('status', 'productsCollection', 'allMaterials', 'ordercollectionn'))->setPaper([0, -16, 2085.98, 296.85], 'landscape');

        return $pdf->stream();

    }

    public function printexportreceived(Status $status, bool $grouped = false, $dateInput = false, $dateOutput = false, $personal = false)
    {
        $statusId = $status->id;

        $making = $status->making;

        if($dateInput == 0){
            $dateInput = false;
        }

        if($dateOutput == 0){
            $dateOutput = false;
        }

        if($personal == 0){
            $personal = false;
        }

        $getPersonal = \App\Domains\Auth\Models\User::find($personal);

        if($grouped){
            $result = ProductStationReceived::query()->with('product_station.product.parent', 'order', 'audi')
                ->whereHas('product_station', function ($query) use ($statusId, $personal) {
                    $query->when($personal, function($querySecond) use ($statusId, $personal){
                        $querySecond->whereHas('station', function ($queryT) use ($statusId, $personal) {
                            $queryT->where('personal_id', $personal);
                        });
                    })
                    ->where('status_id', $statusId);
                })
                ->when($dateInput, function ($query) use($dateInput, $dateOutput) {
                    empty($dateOutput) ?
                        $query->whereBetween('created_at', [$dateInput.' 00:00:00', now()]) :
                        $query->whereBetween('created_at', [$dateInput.' 00:00:00', $dateOutput.' 23:59:59']);
                })
                ->get()
                ->sortBy([
                    ['product_station.product.parent.code', 'asc'],
                    ['product_station.product.color.name', 'asc'],
                    ['product_station.product.size.sort', 'asc']
                ])
                ->groupBy('product_station.product_id')
                ->map(function ($group) {
                    $totalQuantity = $group->sum('quantity');
$priceMaking = isset($group->first()->product_station->product->parent) && isset($group->first()->product_station->product->size) && $group->first()->product_station->product->size->is_extra
    ? $group->first()->product_station->product->parent->price_making_extra
    : (isset($group->first()->product_station->product->parent) ? $group->first()->product_station->product->parent->price_making : 0);
                    $productName = $group->first()->product_station->product->parent_code.' - '. $group->first()->product_station->product->full_name_break; // Asumiendo que el nombre del producto está en el campo 'name'

                    $productStationIds = $group->pluck('product_station.station_id')->unique()->values()->all();

                    return [
                        'product_name' => $productName,
                        'totalQuantity' => $totalQuantity,
                        'priceMaking' => $priceMaking,
                        'productStationsId' => $productStationIds,
                    ];
                });

        }
        else{

        $productsCollectionSecond = collect();


            $result = ProductStationReceived::query()->with('product_station.product.parent', 'order', 'audi')
                ->whereHas('product_station', function ($query) use ($statusId, $personal) {
                    $query->when($personal, function($querySecond) use ($statusId, $personal){
                        $querySecond->whereHas('station', function ($queryT) use ($statusId, $personal) {
                            $queryT->where('personal_id', $personal);
                        });
                    })
                    ->where('status_id', $statusId);
                })
                ->when($dateInput, function ($query) use($dateInput, $dateOutput) {
                    empty($dateOutput) ?
                        $query->whereBetween('created_at', [$dateInput.' 00:00:00', now()]) :
                        $query->whereBetween('created_at', [$dateInput.' 00:00:00', $dateOutput.' 23:59:59']);
                })
                ->get()
                ->sortBy([
                    ['product_station.product.parent.code', 'asc'],
                ])
                ;
 

            foreach($result as $product_st){
                $quantity = $product_st->quantity;

                $productsCollectionSecond->push([
                    'productParentId' => $product_st->product_station->product->parent_id ? $product_st->product_station->product->parent_id : $product_st->product_station->product->id,
                    'producColor' => $product_st->product_station->product->parent_id ? $product_st->product_station->product->color_id : null,
                    'product_name' => $product_st->product_station->product->parent_code.' - <strong>'. $product_st->product_station->product->only_name.'</strong>',
                    'productQuantity' => $quantity,
                    'priceMaking' => isset($product_st->product_station->product->parent) && isset($product_st->product_station->product->size) && $product_st->product_station->product->size->is_extra
                                   ? $product_st->product_station->product->parent->price_making_extra
                                   : (isset($product_st->product_station->product->parent) ? $product_st->product_station->product->parent->price_making : 0),
                    'productStationsId' => $product_st->product_station->station_id,
                ]);
            }

            // $productsCollectionSecond->groupBy('productParentId');


            // dd($productsCollectionSecond);

            $result = $productsCollectionSecond
                ->groupBy(function ($item) {
                    return $item['productParentId'] . '-' . $item['producColor'] . '-' . $item['priceMaking'];
                })
                ->map(function ($group) {
                    $totalQuantity = $group->sum('productQuantity');
                    $priceMaking = $group->first()['priceMaking'];

                    $productStationIds = $group->pluck('productStationsId')->unique()->values()->all();

                    return [
                        'product_name' => $group->first()['product_name'],
                        'productStationsId' => $productStationIds,
                        'priceMaking' => $priceMaking,
                        'totalQuantity' => $totalQuantity,
                        'totalPrice' => $totalQuantity * $priceMaking,
                    ];
                });




            // dd($productsCollectionSecond);


                // ->groupBy(function ($productStation) {
                //     return $productStation->product_station->product->parent ? $productStation->product_station->product->parent->id : $productStation->product_station->product_id;
                // })
                // ->map(function ($group) {
                //     $totalQuantity = $group->sum('quantity');
                //     $productCode = $group->first()->product_station->product->parent_code . ' - ';
                //     $priceMaking = $group->first()->product_station->product->size->is_extra ? $group->first()->product_station->product->parent->price_making_extra : $group->first()->product_station->product->parent->price_making;
                //     $productName = $group->first()->product_station->product->parent ? $group->first()->product_station->product->parent->name : $group->first()->product_station->product->name;

                //     $productStationIds = $group->pluck('product_station_id')->unique()->values()->all();

                //     // Multiplicación del valor de $priceMaking por quantity y suma de la multiplicación total
                //     $totalPriceMaking = $group->sum(function ($item) use ($priceMaking) {
                //         return $priceMaking * $item->quantity;
                //     });

                //     // Agrupación de los totales por $priceMaking
                //     $priceMakingGrouped = $group->groupBy(function ($item) use ($priceMaking) {
                //         return $priceMaking;
                //     })->map(function ($items) use ($priceMaking) {
                //         return $items->sum('quantity') * $priceMaking;
                //     });

                //     return [
                //         'product_name' => $productCode . $productName,
                //         'totalQuantity' => $totalQuantity,
                //         'productStationsId' => $productStationIds,
                //         'totalPriceMaking' => $totalPriceMaking,
                //         'priceMakingGrouped' => $priceMakingGrouped,
                //     ];
                // });

                // dd($result);

        }    

        $res = ProductStationReceived::query()
            ->with('product_station.product.parent', 'order', 'audi')
            ->whereHas('product_station', function ($query) use($statusId) {
                $query->where('status_id', $statusId);
            })
            ->get();

        $oldestDate = $res->min('created_at');
        $newestDate = $res->max('created_at');

        $pdf = PDF::loadView('backend.information.print-export-received',compact('status', 'oldestDate', 'newestDate', 'result', 'making', 'grouped', 'dateInput', 'dateOutput', 'getPersonal'))->setPaper('a4', 'portrait')
                  ->setWarnings(false);

        return $pdf->stream();
    }



public function printexportreceivedproduction(Status $status, bool $grouped = false, $dateInput = false, $dateOutput = false, $personal = false)
{
    if(!$dateOutput || !$dateInput) {
        return response("<script>window.close();</script>")->header('Content-Type', 'text/html');
    }

    $making = $status->making;

    $query = ProductionBatchItemHistory::with([
            'production_batch_item.batch',
            'production_batch_item.product.parent',
            'production_batch_item.product.size',
            'production_batch_item.product.color'
        ])
        ->where('receive', '>', 0)
        ->whereBetween('created_at', [$dateInput.' 00:00:00', $dateOutput.' 23:59:59']);

    // Filtrar por personal si es necesario
    if ($personal) {
        $query->whereHas('production_batch_item.batch', function($q) use ($personal) {
            $q->where('personal_id', $personal);
        });
    }
    // dd($grouped);

    // Obtener y agrupar los resultados
    if ($grouped) {
        // Agrupar por producto y sumar cantidades
        $result = $query
            ->get()
            ->sortBy([
                ['production_batch_item.product.parent.code', 'asc'],
                ['production_batch_item.product.color.name', 'asc'],
                ['production_batch_item.product.size.sort', 'asc']
            ])
            ->groupBy('production_batch_item.product_id')
            ->map(function($items) {
                $firstItem = $items->first();
                $product = $firstItem->production_batch_item->product;
                $isExtra = $product->size->is_extra;
                $price = $isExtra ? $product->parent->price_making_extra : $product->parent->price_making;

                return (object) [
                    'product' => $product,
                    'total_quantity' => $items->sum('receive'),
                    'items' => $items,
                    'price' => $price,
                    'is_extra' => $isExtra,
                    'first_item' => $items->first(),
                    'total_price' => $items->sum(function ($item) use ($price) {
                        return $item->receive * $price;
                    }),
                ];
            });
    } else {
        $result = $query
            ->get()
            ->sortBy([
                ['production_batch_item.product.parent.code', 'asc'],
                ['production_batch_item.product.color.name', 'asc'],
                ['production_batch_item.product.size.sort', 'asc']
            ])
            ->groupBy([
                function ($item) {
                    $product = $item->production_batch_item->product;
                    return $product->parent_id .' - '. $product->color_id . '-' . $product->size->is_extra;
                }
            ])
            ->map(function ($items) {
                $firstItem = $items->first();
                $product = $firstItem->production_batch_item->product;
                $isExtra = $product->size->is_extra;
                $price = $isExtra ? $product->parent->price_making_extra : $product->parent->price_making;
                
                return (object) [
                    'product' => $product,
                    'total_quantity' => $items->sum('receive'),
                    'price' => $price,
                    'is_extra' => $isExtra,
                    'total_price' => $items->sum(function ($item) use ($price) {
                        return $item->receive * $price;
                    }),
                ];
            })
            ->values();
    }

    // Obtener fechas extremas
    $oldestDate = $query->oldest('created_at')->value('created_at');
    $newestDate = $query->latest('created_at')->value('created_at');

    $getPersonal = $personal ? User::find($personal) : null;

    $pdf = PDF::loadView('backend.information.print-export-received-production', compact(
        'status', 
        'oldestDate', 
        'newestDate', 
        'result', 
        'making', 
        'grouped', 
        'dateInput', 
        'dateOutput', 
        'getPersonal'
    ))->setPaper('a4', 'portrait')
      ->setWarnings(false);

    return $pdf->stream();

}

    public function printexporthistory(Status $status, bool $grouped = false, $dateInput = false, $dateOutput = false, $personal = false)
    {
        $statusId = $status->id;

        if($dateInput == 0){
            $dateInput = false;
        }

        if($dateOutput == 0){
            $dateOutput = false;
        }

        if($personal == 0){
            $personal = false;
        }

        $getPersonal = \App\Domains\Auth\Models\User::find($personal);


        if($grouped){
            $result = ProductStation::query()->with('product.parent', 'status')
                ->where('status_id', $status->id)
                ->when($personal, function($querySecond) use ($statusId, $personal){
                    $querySecond->whereHas('station', function ($queryT) use ($statusId, $personal) {
                        $queryT->where('personal_id', $personal);
                    });
                })
                ->when($dateInput, function ($query) use($dateInput, $dateOutput) {
                    empty($dateOutput) ?
                        $query->whereBetween('created_at', [$dateInput.' 00:00:00', now()]) :
                        $query->whereBetween('created_at', [$dateInput.' 00:00:00', $dateOutput.' 23:59:59']);
                })
                ->get()
                ->groupBy('product_id')
                ->map(function ($group) {
                    $totalQuantity = $group->sum('quantity');
                    $totalOpen = $group->sum(function ($productStation) {
                        return $productStation->metadata['open'] ?? 0;
                    });
                    $totalClosed = $group->sum(function ($productStation) {
                        return $productStation->metadata['closed'] ?? 0;
                    });
                    $productName = $group->first()->product->full_name_clear; // Asumiendo que el nombre del producto está en el campo 'name'
                    return [
                        'product_name' => $productName,
                        'total_open' => $totalOpen,
                        'total_closed' => $totalClosed,
                        'total_quantity' => $totalOpen + $totalClosed,
                        'totalQuantity' => $totalQuantity,
                    ];
                });

        }
        else{
            $result = ProductStation::query()->with('product.parent', 'status')
                ->where('status_id', $status->id)
                ->when($personal, function($querySecond) use ($statusId, $personal){
                    $querySecond->whereHas('station', function ($queryT) use ($statusId, $personal) {
                        $queryT->where('personal_id', $personal);
                    });
                })
                ->when($dateInput, function ($query) use($dateInput, $dateOutput) {
                    empty($dateOutput) ?
                        $query->whereBetween('created_at', [$dateInput.' 00:00:00', now()]) :
                        $query->whereBetween('created_at', [$dateInput.' 00:00:00', $dateOutput.' 23:59:59']);
                })
                ->get()
                ->groupBy(function ($productStation) {
                    return $productStation->product->parent ? $productStation->product->parent->id : $productStation->product_id;
                })
                ->map(function ($group) {
                    $totalQuantity = $group->sum('quantity');
                    $totalOpen = $group->sum(function ($productStation) {
                        return $productStation->metadata['open'] ?? 0;
                    });
                    $totalClosed = $group->sum(function ($productStation) {
                        return $productStation->metadata['closed'] ?? 0;
                    });
                    $productName = $group->first()->product->parent ? $group->first()->product->parent->name : $group->first()->product->name;
                    return [
                        'product_name' => $productName,
                        'total_open' => $totalOpen,
                        'total_closed' => $totalClosed,
                        'total_quantity' => $totalOpen + $totalClosed,
                        'totalQuantity' => $totalQuantity,
                    ];
                });

        }    

        $res = ProductStation::query()
            ->with('product.parent', 'status')
            ->where('status_id', $status->id)
            ->when($dateInput, function ($query) use($dateInput, $dateOutput) {
                empty($dateOutput) ?
                    $query->whereBetween('created_at', [$dateInput.' 00:00:00', now()]) :
                    $query->whereBetween('created_at', [$dateInput.' 00:00:00', $dateOutput.' 23:59:59']);
            })
            ->get();

        $oldestDate = $res->min('created_at');
        $newestDate = $res->max('created_at');

        $pdf = PDF::loadView('backend.information.print-export-history',compact('status', 'oldestDate', 'newestDate', 'result', 'dateInput', 'dateOutput', 'getPersonal'))->setPaper('a4')
                  ->setWarnings(false);

        return $pdf->stream();
    }


    public function printexportquantities(Status $status, bool $grouped = false, ?bool $allStatus = false)
    {
        if($grouped){
            $result = ProductStation::query()->with('product.parent', 'status')
                ->when(!$allStatus, function ($query) use ($status) {
                    return $query->where('status_id', $status->id);
                })
                ->where('active', true)
                ->get()
                ->groupBy('product_id')
                ->map(function ($group) {
                    $totalQuantity = $group->sum('quantity');
                    $totalOpen = $group->sum(function ($productStation) {
                        return $productStation->metadata['open'] ?? 0;
                    });
                    $totalClosed = $group->sum(function ($productStation) {
                        return $productStation->metadata['closed'] ?? 0;
                    });
                    $productName = $group->first()->product->full_name_clear; // Asumiendo que el nombre del producto está en el campo 'name'
                    return [
                        'product_name' => $productName,
                        'total_open' => $totalOpen,
                        'total_closed' => $totalClosed,
                        'total_quantity' => $totalOpen + $totalClosed,
                    ];
                });

        }
        else{
            $result = ProductStation::query()->with('product.parent', 'status')
                ->when(!$allStatus, function ($query) use ($status) {
                    return $query->where('status_id', $status->id);
                })
                ->where('active', true)
                ->get()
                ->groupBy(function ($productStation) {
                    return $productStation->product->parent ? $productStation->product->parent->id : $productStation->product_id;
                })
                ->map(function ($group) {
                    $totalQuantity = $group->sum('quantity');
                    $totalOpen = $group->sum(function ($productStation) {
                        return $productStation->metadata['open'] ?? 0;
                    });
                    $totalClosed = $group->sum(function ($productStation) {
                        return $productStation->metadata['closed'] ?? 0;
                    });
                    $productName = $group->first()->product->parent ? $group->first()->product->parent->name : $group->first()->product->name;
                    return [
                        'product_name' => $productName,
                        'total_open' => $totalOpen,
                        'total_closed' => $totalClosed,
                        'total_quantity' => $totalOpen + $totalClosed,
                    ];
                });
        }    


        $res = ProductStation::query()
            ->with('product.parent', 'status')
            ->when(!$allStatus, function ($query) use ($status) {
                return $query->where('status_id', $status->id);
            })
            ->where('active', true)
            ->get();

        $oldestDate = $res->min('created_at');
        $newestDate = $res->max('created_at');


        $pdf = PDF::loadView('backend.information.print-export-quantities',compact('status', 'oldestDate', 'newestDate', 'result', 'allStatus'))->setPaper('a4', 'portrait')
                  ->setWarnings(false);

        return $pdf->stream();
    }

    public function pending_materia(Status $status)
    {
        if(($status->active != true) || !$status->initial_lot){
            abort(401);
        }

        $query = ProductStation::query()->with('product', 'status', 'station')
            ->where('status_id', $status->id)
            ->where('active', true)
            ->where('not_consider', false)
            ->whereHas('station', function ($query) {
                $query->where('consumption', false);
            })
            ->get();

        $consumptionCollect = collect();
        $ordercollection = collect();
        $productsCollection = collect();

        $parentQuantities = [];

        foreach ($query as $product_statione) {
            $quantity = $product_statione->quantity;

            $ordercollection->push([
                'order_id' => $product_statione->order_id,
                'station_id' => $product_statione->station_id,
            ]);

            $productParentId = $product_statione->product->parent_id ?? $product_statione->product_id;

            $productsCollection->push([
                'productId' => $product_statione->id,
                'productParentId' => $productParentId,
                'productParentName' => $product_statione->product->only_name ?? null,
                'productParentCode' => $product_statione->product->parent_code ?? null,
                'productOrder' => $product_statione->order_id,
                'productName' => $product_statione->product->parent->name ?? null,
                'productColor' => $product_statione->product->color_id,
                'productColorName' => $product_statione->product->color->name ?? '',
                'productSizeName' => $product_statione->product->size->name ?? '',
                'productSizeSort' => $product_statione->product->size->sort ?? '',
                'productQuantity' => $quantity,
                'isService' => !$product_statione->product->parent_id ? true : false,
                'customer' => $product_statione->order->user_name ?? null,
                'stationId' => $product_statione->station_id ?? null,
                'orderId' => $product_statione->order_id ?? null,
            ]);

            if (!isset($parentQuantities[$productParentId])) {
                $parentQuantities[$productParentId] = 0;
            }
            $parentQuantities[$productParentId] += $quantity;

            if ($product_statione->product_order->gettAllConsumptionSecond($quantity) != 'empty') {
                foreach ($product_statione->product_order->gettAllConsumptionSecond($quantity) as $key => $consumption) {
                    $consumptionCollect->push([
                        'order' => $product_statione->order_id,
                        'product_order_id' => $product_statione->product_order->id,
                        'material_name' => $consumption['material'],
                        'part_number' => $consumption['part_number'],
                        'material_id' => $key,
                        'unit' => $consumption['unit'],
                        'unit_measurement' => $consumption['unit_measurement'],
                        'vendor' => $consumption['vendor'],
                        'family' => $consumption['family'],
                        'quantity' => $consumption['quantity'],
                        'stock' => $consumption['stock'],
                    ]);
                }
            }
        }

        $materials = $consumptionCollect->groupBy('material_id')->map(function ($row) {
            return [
                'order' => $row[0]['order'],
                'product_order_id' => $row[0]['product_order_id'],
                'material_name' => $row[0]['material_name'],
                'part_number' => $row[0]['part_number'],
                'material_id' => $row[0]['material_id'],
                'unit' => $row[0]['unit'],
                'unit_measurement' => $row[0]['unit_measurement'],
                'vendor' => $row[0]['vendor'],
                'family' => $row[0]['family'],
                'quantity' => $row->sum('quantity'),
                'stock' => $row[0]['stock'],
            ];
        });

        $allMaterials = $materials->map(function ($product) {
            return [
                'order' => $product['order'],
                'material_name' => $product['material_name'],
                'part_number' => $product['part_number'],
                'unit_measurement' => $product['unit_measurement'],
                'stock' => $product['stock'],
                'quantity' => $product['quantity'],
                'vendor' => $product['vendor'],
            ];
        });

        $ordercollectionn = $ordercollection->groupBy(['order_id', 'station_id'])->toArray();

        $productsCollection = $productsCollection->sortBy('productParentId');
        $groupedProducts = $productsCollection->groupBy('productParentId');

        // dd($parentQuantities);

        $pdf = PDF::loadView('backend.information.print-export-pending', compact('status', 'productsCollection', 'allMaterials', 'ordercollectionn', 'parentQuantities', 'groupedProducts'))
            ->setPaper('a4', 'portrait')
            ->setWarnings(false);

        return $pdf->stream();
    }


    public function pending_materia_grouped(Status $status, bool $additional = false)
    {
        if(($status->active != true) || !$status->initial_lot){
            abort(401);
        }

        $query = ProductStation::query()->with('product', 'status', 'station')
            ->where('status_id', $status->id)
            ->where('active', true)
            ->where('not_consider', false)
            ->whereHas('station', function ($query) {
                $query->where('consumption', false);
            })
            ->get();

        $consumptionCollect = collect();
        $ordercollection = collect();
        $productsCollection = collect();
        $materialsCollectionSecond = collect();

        $parentQuantities = [];

        if($additional){
            
            $materialsAdditionals = Additional::with('material.color', 'product.vendor', 'product.size')->where('type', 'feedstock')->where('branch_id', 0)->where('date_entered', null)->where('user_id', Auth::id())->get();

            foreach ($materialsAdditionals as $materialsAdd) {

                $materialsCollectionSecond->push([
                    'order' => null,
                    'product_order_id' => null,
                    'material_name' => $materialsAdd->material->full_name_clear ?? null,
                    'part_number' => $materialsAdd->material->part_number ?? null,
                    'material_id' => $materialsAdd->material->id,
                    'unit' =>  $materialsAdd->quantity,
                    'unit_measurement' => $materialsAdd->material->unit_measurement ?? null,
                    'vendor' => $materialsAdd->material->vendor->short_name ?? null,
                    'family' => $materialsAdd->material->family->name ?? null,
                    'quantity' => $materialsAdd->quantity,
                    'stock' => $materialsAdd->material->stock,
                    'additional' => true,
                ]);
            }
        }


        foreach ($query as $product_statione) {
            $quantity = $product_statione->quantity;

            $ordercollection->push([
                'order_id' => $product_statione->order_id,
                'station_id' => $product_statione->station_id,
            ]);

            $productParentId = $product_statione->product->parent_id ?? $product_statione->product_id;

            $productsCollection->push([
                'productId' => $product_statione->id,
                'productParentId' => $productParentId,
                'productParentName' => $product_statione->product->only_name ?? null,
                'productParentCode' => $product_statione->product->parent_code ?? null,
                'productOrder' => $product_statione->order_id,
                'productName' => $product_statione->product->parent->name ?? null,
                'productColor' => $product_statione->product->color_id,
                'productColorName' => $product_statione->product->color->name ?? '',
                'productSizeName' => $product_statione->product->size->name ?? '',
                'productSizeSort' => $product_statione->product->size->sort ?? '',
                'productQuantity' => $quantity,
                'isService' => !$product_statione->product->parent_id ? true : false,
                'customer' => $product_statione->order->user_name ?? null,
                'stationId' => $product_statione->station_id ?? null,
                'orderId' => $product_statione->order_id ?? null,
            ]);

            if (!isset($parentQuantities[$productParentId])) {
                $parentQuantities[$productParentId] = 0;
            }
            $parentQuantities[$productParentId] += $quantity;

            if ($product_statione->product_order->gettAllConsumptionSecond($quantity) != 'empty') {
                foreach ($product_statione->product_order->gettAllConsumptionSecond($quantity) as $key => $consumption) {
                    $consumptionCollect->push([
                        'order' => $product_statione->order_id,
                        'product_order_id' => $product_statione->product_order->id,
                        'material_name' => $consumption['material'],
                        'part_number' => $consumption['part_number'],
                        'material_id' => $key,
                        'unit' => $consumption['unit'],
                        'unit_measurement' => $consumption['unit_measurement'],
                        'vendor' => $consumption['vendor'],
                        'family' => $consumption['family'],
                        'quantity' => $consumption['quantity'],
                        'stock' => $consumption['stock'],
                        'additional' => false,
                    ]);
                }
            }
        }

        // dd($materialsCollectionSecond);

        if($additional){
            $consumptionCollect = $consumptionCollect->merge($materialsCollectionSecond);
        }


        $results = $consumptionCollect->groupBy(function ($item) {
            return $item['material_id'] . '_' . $item['additional'];
        })->map(function ($items) {
            $quantitySum = $items->sum('quantity');
            $item = $items->first();
            $item['quantity'] = $quantitySum;
            if (!$item['additional']) {
                $item['quantity_minus_stock'] = $quantitySum - $item['stock'];
            } else {
                $item['quantity_minus_stock'] = $quantitySum;
            }
            return $item;
        })->values();


        $filtered = $results->filter(function ($item) {
            return $item['quantity_minus_stock'] > 0;
        });

        // dd($filtered);

        $materials = $filtered
        ->groupBy('vendor')
        ->map(function ($itemsByVendor) {
            return $itemsByVendor->groupBy('material_id')
                ->map(function ($itemsByMaterial) {
                    $sumQuantityMinusStock = $itemsByMaterial->sum('quantity_minus_stock');
                    $item = $itemsByMaterial->first();
                    $item['quantity_minus_stock'] = $sumQuantityMinusStock;
                    return $item;
                })
                ->values();
        });

        // dd($materials);

        $ordercollectionn = $ordercollection->groupBy(['order_id', 'station_id'])->toArray();

        $productsCollection = $productsCollection->sortBy('productParentId');
        $groupedProducts = $productsCollection->groupBy('productParentId');

        // dd($parentQuantities);

        $pdf = PDF::loadView('backend.information.print-export-pending-grouped', compact('status', 'materialsCollectionSecond', 'productsCollection', 'materials', 'ordercollectionn', 'parentQuantities', 'groupedProducts'))
            ->setPaper('a4', 'portrait')
            ->setWarnings(false);

        return $pdf->stream();
    }


    public function pending_vendor(Status $status)
    {
        if(($status->active != true) || !$status->supplier){
            abort(401);
        }

        $query = ProductStation::query()->with('product', 'status')
            ->where('status_id', $status->id)
            ->where('active', true)
            ->where('not_consider', false)
            ->get();

        $ordercollection = collect();
        $productsCollection = collect();

        $parentQuantities = [];

        foreach ($query as $product_statione) {
            $quantity = $product_statione->quantity;

            $ordercollection->push([
                'order_id' => $product_statione->order_id,
                'station_id' => $product_statione->station_id,
            ]);

            $productParentId = $product_statione->product->parent_id ?? $product_statione->product_id;

            $productsCollection->push([
                'productId' => $product_statione->id,
                'productParentId' => $productParentId,
                'productParentName' => $product_statione->product->only_name ?? null,
                'productParentCode' => $product_statione->product->parent_code ?? null,
                'productOrder' => $product_statione->order_id,
                'productName' => $product_statione->product->full_name_clear ?? null,
                'productColor' => $product_statione->product->color_id,
                'productColorName' => $product_statione->product->color->name ?? '',
                'productSizeName' => $product_statione->product->size->name ?? '',
                'productSizeSort' => $product_statione->product->size->sort ?? '',
                'productQuantity' => $quantity,
                'isService' => !$product_statione->product->parent_id ? true : false,
                'customer' => $product_statione->order->user_name ?? null,
                'stationId' => $product_statione->station_id,
                'orderId' => $product_statione->order_id,
            ]);

            if (!isset($parentQuantities[$productParentId])) {
                $parentQuantities[$productParentId] = 0;
            }
            $parentQuantities[$productParentId] += $quantity;
        }

        $ordercollectionn = $ordercollection->groupBy(['order_id', 'station_id'])->toArray();

        $productsCollection = $productsCollection->sortBy('productParentId');
        $groupedProducts = $productsCollection->groupBy('productParentId');

        // dd($parentQuantities);

        $pdf = PDF::loadView('backend.information.print-export-vendor', compact('status', 'productsCollection', 'ordercollectionn', 'parentQuantities', 'groupedProducts'))
            ->setPaper('a4', 'portrait')
            ->setWarnings(false);

        return $pdf->stream();
    }


    public function pending_vendor_grouped(Status $status, bool $additional = false)
    {
        if(($status->active != true) || !$status->supplier){
            abort(401);
        }

        $query = ProductStation::query()->with('product', 'status')
            ->where('status_id', $status->id)
            ->where('active', true)
            ->where('not_consider', false)
            ->get();

        $ordercollection = collect();
        $productsCollection = collect();

        $parentQuantities = [];

        if($additional){
            
            $productsCollectionSecond = collect();

            $productsAdditionals = Additional::with('product.color', 'product.parent', 'product.size')->where('type', 'vendor')->where('branch_id', 0)->where('date_entered', null)->where('user_id', Auth::id())->get();


            foreach ($productsAdditionals as $product_st) {
                $quantitySecond = $product_st->quantity;

                $productParentIdSecond = $product_st->product->parent_id ?? $product_st->product_id;

                $productsCollectionSecond->push([
                    'productId' => $product_st->id,
                    'productParentId' => $productParentIdSecond,
                    'productParentName' => $product_st->product->only_name ?? null,
                    'productParentCode' => $product_st->product->parent_code ?? null,
                    'productOrder' => $product_st->order_id,
                    'productName' => $product_st->product->parent->name ?? null,
                    'productColor' => $product_st->product->color_id,
                    'productColorName' => $product_st->product->color->name ?? '',
                    'productSizeName' => $product_st->product->size->name ?? '',
                    'productSizeSort' => $product_st->product->size->sort ?? '',
                    'productQuantity' => $quantitySecond,
                    'isService' => !$product_st->product->parent_id ? true : false,
                    'customer' => $product_st->order->user_name ?? null,
                    'stationId' => $product_st->station_id,
                    'orderId' => $product_st->order_id,
                    'vendorId' => $product_st->product->vendor_id,
                    'vendorName' => $product_st->product->parent->vendor->name ?? null,
                    'vendorAddress' => $product_st->product->parent->vendor->address ?? null,
                    'vendorCity' => $product_st->product->parent->vendor->city->city ?? null,
                    'vendorPhone' => $product_st->product->parent->vendor->phone ?? null,
                    'vendorEmail' => $product_st->product->parent->vendor->email ?? null,
                    'vendorRfc' => $product_st->product->parent->vendor->rfc ?? null,
                ]);

                if (!isset($parentQuantities[$productParentIdSecond])) {
                    $parentQuantities[$productParentIdSecond] = 0;
                }
                $parentQuantities[$productParentIdSecond] += $quantitySecond;
            }
        }

        foreach ($query as $product_statione) {
            $quantity = $product_statione->quantity;

            $ordercollection->push([
                'order_id' => $product_statione->order_id,
                'station_id' => $product_statione->station_id,
            ]);

            $productParentId = $product_statione->product->parent_id ?? $product_statione->product_id;

            $productsCollection->push([
                'productId' => $product_statione->id,
                'productParentId' => $productParentId,
                'productParentName' => $product_statione->product->only_name ?? null,
                'productParentCode' => $product_statione->product->parent_code ?? null,
                'productOrder' => $product_statione->order_id,
                'productName' => $product_statione->product->parent->name ?? null,
                'productColor' => $product_statione->product->color_id,
                'productColorName' => $product_statione->product->color->name ?? '',
                'productSizeName' => $product_statione->product->size->name ?? '',
                'productSizeSort' => $product_statione->product->size->sort ?? '',
                'productQuantity' => $quantity,
                'isService' => !$product_statione->product->parent_id ? true : false,
                'customer' => $product_statione->order->user_name ?? null,
                'stationId' => $product_statione->station_id,
                'orderId' => $product_statione->order_id,
                'vendorId' => $product_statione->product->vendor_id,
                'vendorName' => $product_statione->product->parent->vendor->name ?? null,
                'vendorAddress' => $product_statione->product->parent->vendor->address ?? null,
                'vendorCity' => $product_statione->product->parent->vendor->city->city ?? null,
                'vendorPhone' => $product_statione->product->parent->vendor->phone ?? null,
                'vendorEmail' => $product_statione->product->parent->vendor->email ?? null,
                'vendorRfc' => $product_statione->product->parent->vendor->rfc ?? null,
            ]);

            if (!isset($parentQuantities[$productParentId])) {
                $parentQuantities[$productParentId] = 0;
            }
            $parentQuantities[$productParentId] += $quantity;
        }

        if($additional){
            $productsCollection  = $productsCollection->merge($productsCollectionSecond);            
        }

        $ordercollectionn = $ordercollection->groupBy(['order_id', 'station_id'])->toArray();

        $productsCollection = $productsCollection->sortBy('productParentId');
        $groupedProducts = $productsCollection->groupBy(['productParentId']);

        $groupedProductsSecond = $productsCollection->groupBy(['vendorName', 'productParentId']);

        // Ordenar cada grupo por productColorName y productSizeSort
        $groupedProductsSecond = $groupedProductsSecond->map(function ($vendorGroup) {
            return $vendorGroup->map(function ($parentGroup) {
                return $parentGroup->sortBy(function ($product) {
                    return $product['productColorName'] . ' ' . $product['productSizeSort'];
                });
            });
        });

        // dd($groupedProductsSecond);

        $pdf = PDF::loadView('backend.information.print-export-vendor-grouped', compact('status', 'productsCollection', 'ordercollectionn', 'parentQuantities', 'groupedProducts', 'groupedProductsSecond'))
            ->setPaper('a4', 'portrait')
            ->setWarnings(false);

        return $pdf->stream();
    }

    public function add_to_vendor(Status $status)
    {
        if(($status->active != true) || !$status->supplier){
            abort(401);
        }

        return view('backend.information.add-to-vendor', compact('status'));
    }

    public function add_to_materia(Status $status)
    {
        if(($status->active != true) || !$status->initial_lot){
            abort(401);
        }

        return view('backend.information.add-to-materia', compact('status'));
    }

    public function select2LoadMore(Request $request)
    {
        $search = $request->get('search');
        $data = Status::select(['id', 'name'])->where('name', 'like', '%' . $search . '%')->orderBy('level')->paginate(5);
        return response()->json(['items' => $data->toArray()['data'], 'pagination' => $data->nextPageUrl() ? true : false]);
    }
}
