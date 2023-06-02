<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use Illuminate\Http\Request;

class BatchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.ticket.index');
    }

    public function destroy(Batch $batch)
    {
        if($batch->id){
            $batch->delete();
        }

        return redirect()->back()->withFlashSuccess(__('The batch was successfully deleted.'));
    }
}
