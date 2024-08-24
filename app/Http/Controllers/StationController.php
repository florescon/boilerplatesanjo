<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Station;
use App\Models\StationPreconsumption;
use PDF;

class StationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.station.index');
    }

    public function edit(Station $station)
    {
        $vvar =  $station->created_at->timestamp;

        return view('backend.station.edit-station', compact('station', 'vvar'));
    }

    public function ticket(Station $station)
    {
        $pdf = PDF::loadView('backend.station.ticket-station',compact('station'))->setPaper([0, -16, 2085.98, 296.85], 'landscape');

        return $pdf->stream();
    }

    public function output(Station $station)
    {
        if(!$station->status->final_process){
            abort(401);
        }

        return view('backend.station.output-station', compact('station'));
    }

    public function checklist(Station $station)
    {
        $station->load('material_order.material');

        $groupedMaterials = $station->material_order->where('manual', false)->groupBy('material_id')->map(function ($group) {
            return [
                'order_id' => $group[0]->order_id,
                'material' => $group[0]->material->full_name,
                'price' => $group[0]->price,
                'unit_quantity' => $group[0]->unit_quantity,
                'updated_at' => $group[0]->updated_at,
                'unit' => $group[0]->material->unit_name_label,
                'sum_quantity' => $group->sum('quantity'),
                'sum' => $group->sum('quantity').' '.$group[0]->material->unit_name_label,
            ];
        });

        $preconsumptions = StationPreconsumption::where('station_id', $station->id)->get();

        // Create a map of material_id => quantity
        $preconsumptionMap = $preconsumptions->pluck('quantity', 'material_id')->toArray();
        $preconsumptionRMap = $preconsumptions->pluck('received', 'material_id')->toArray();
        $preconsumptionPMap = $preconsumptions->pluck('processed', 'material_id')->toArray();


        $quantities = $station->material_order->groupBy('material_id')->mapWithKeys(function ($group) use ($preconsumptionMap) {
            $material_id = $group[0]->material_id;
            $quantity = $preconsumptionMap[$material_id] ?? null;
            // $quantity = $preconsumptionMap[$material_id] ?? $group->sum('quantity');
            return [$material_id => $quantity];
        })->toArray();

        $received = $station->material_order->groupBy('material_id')->mapWithKeys(function ($group) use ($preconsumptionRMap) {
            $material_id = $group[0]->material_id;
            $received = $preconsumptionRMap[$material_id] ?? null;
            return [$material_id => $received > 0 ? $received : null];
        })->toArray();


        $processed = $station->material_order->groupBy('material_id')->mapWithKeys(function ($group) use ($preconsumptionPMap) {
            $material_id = $group[0]->material_id;
            $processed = $preconsumptionPMap[$material_id] ?? null;
            return [$material_id => $processed > 0 ? $processed : null];
        })->toArray();

        $pdf = PDF::loadView('backend.station.checklist-station',compact('station', 'groupedMaterials', 'quantities', 'received', 'processed'))->setPaper('a4', 'portrait');

        return $pdf->stream();
    }

    public function checklist_details(Station $station)
    {
        return view('backend.station.checklist-details', compact('station'));
    }

    public function checklist_ticket(Station $station)
    {
        $station->load('material_order.material');

        $groupedMaterials = $station->material_order->groupBy('material_id')->map(function ($group) {
            return [
                'order_id' => $group[0]->order_id,
                'material' => $group[0]->material->full_name,
                'price' => $group[0]->price,
                'unit_quantity' => $group[0]->unit_quantity,
                'sum' => $group->sum('quantity').' '.$group[0]->material->unit_name_label,
            ];
        });

        $pdf = PDF::loadView('backend.station.checklist-ticket-station',compact('station', 'groupedMaterials'))->setPaper([0, -16, 2085.98, 296.85], 'landscape');

        return $pdf->stream();
    }

    public function deleted()
    {
        return view('backend.station.deleted');
    }

    public function destroy(Station $station)
    {
        if($station->id){
            $station->delete();
        }

        return redirect()->route('admin.station.index')->withFlashSuccess(__('The station was successfully deleted'));
    }

}
