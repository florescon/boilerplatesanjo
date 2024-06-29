<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Station;
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

    public function checklist(Station $station)
    {
        $station->load('material_order');

        $groupedMaterials = $station->material_order->groupBy('material_id')->map(function ($group) {
            return [
                'order_id' => $group[0]->order_id,
                'material' => $group[0]->material->full_name,
                'price' => $group[0]->price,
                'unit_quantity' => $group[0]->unit_quantity,
                'created_at' => $group[0]->created_at,
                'sum' => $group->sum('quantity').' '.$group[0]->material->unit_name_label,
            ];
        });

        $pdf = PDF::loadView('backend.station.checklist-station',compact('station', 'groupedMaterials'))->setPaper('a4', 'portrait');

        return $pdf->stream();
    }

    public function checklist_ticket(Station $station)
    {
        $station->load('material_order');

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
