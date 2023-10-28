<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\BatchProduct;
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
        return view('backend.batch.index');
    }

    public function index_conformed()
    {
        return view('backend.batch.index-conformed');
    }

    public function index_manufacturing()
    {
        return view('backend.batch.index-manufacturing');
    }

    public function index_personalization()
    {
        return view('backend.batch.index-personalization');
    }

    public function index_shipment()
    {
        return view('backend.batch.index-shipment');
    }


    public function destroy(Batch $batch)
    {
        foreach($batch->batch_product as $batch_product){
            $batch_product->parent()->increment('active', abs($batch_product->quantity));
            $batch_product->children()->update(['active' => 0]);
        }

        if($batch->id){
            $batch->update(['active' => 0]);
            $batch->delete();
        }

        return redirect()->back()->withFlashSuccess(__('The batch was successfully deleted.'));
    }
}
