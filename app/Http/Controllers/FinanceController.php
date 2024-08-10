<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Finance;
use PDF;

class FinanceController extends Controller
{
    public function print(Finance $finances)
    {
        $pdf = PDF::loadView('backend.store.finance.print-finance',compact('finances'))->setPaper([0, 0, 685.98, 296.85], 'landscape');

        return $pdf->stream();
    }

    public function deleted()
    {
        return view('backend.store.deleted-finances');
    }
}
