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
