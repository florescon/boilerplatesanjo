<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceOrder;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DB;
use PDF;

class ServiceOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.serviceorder.index');
    }

    public function printexportserviceorder($dateInput = false, $dateOutput = false, $personal = false, ?bool $grouped = false)
    {
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

        $result = ServiceOrder::query()->with('personal', 'product_service_orders', 'service_type', 'order.user', 'order.departament')
            ->when($personal, function($query) use ($personal){
                $query->where('user_id', $personal);
            })
            ->when($dateInput, function ($query) use($dateInput, $dateOutput) {
                empty($dateOutput) ?
                    $query->whereBetween('created_at', [$dateInput.' 00:00:00', now()]) :
                    $query->whereBetween('created_at', [$dateInput.' 00:00:00', $dateOutput.' 23:59:59']);
            })
            ->get()
            ->map(function ($group) {
                return [
                    'service_type' => $group->service_type->name,
                    'total' => $group->total_products,
                    'customer' => optional($group->order)->user_name,
                    'created_at' => $group->date_for_humans,                        
                ];
            });

        if ($grouped) {
            $result = collect($result)
                ->groupBy('service_type')  // Agrupar por el campo 'service_type'
                ->map(function($group) {
                    return [
                        'service_type' => $group->first()['service_type'],  // Obtener el nombre del 'service_type'
                        'total' => $group->sum('total'), // Sumar el 'total' de todos los elementos del grupo
                        'customer' => '',
                        'created_at' => '',
                    ];
                })
                ->values();  // Reindexar para obtener un array numerado
        }

        $pdf = PDF::loadView('backend.serviceorder.print-export-service-order',compact('result', 'dateInput', 'dateOutput', 'getPersonal', 'grouped'))->setPaper('a4', 'portrait')
                  ->setWarnings(false);

        return $pdf->stream();
    }

    public function deleted()
    {
        return view('backend.serviceorder.deleted');
    }
}
