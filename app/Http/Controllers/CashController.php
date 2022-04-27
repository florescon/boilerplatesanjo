<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cash;
use PDF;
use Carbon\Carbon;

class CashController extends Controller
{
    public function show(Cash $box)
    {
        return view('backend.store.box.show-box', compact('box'));
    }

    public function ticket(Cash $box)
    {
        $pdf = PDF::loadView('backend.store.box.print-box', compact('box'))->setPaper([0, 0, 1385.98, 296.85], 'landscape');
        return $pdf->stream();
    }

    public function ticketCash(Cash $box)
    {
        $box->load(['finances' => function ($query) {
               $query->where('payment_method_id', 1);
            }]
        );

        $pdf = PDF::loadView('backend.store.box.print-box-cash', compact('box'))->setPaper([0, 0, 1385.98, 296.85], 'landscape');
        return $pdf->stream();
    }

    public function ticketCashOut(Cash $box)
    {
        $box->load(['finances' => function ($query) {
               $query->where('payment_method_id', '!=', 1);
            }]
        );

        $pdf = PDF::loadView('backend.store.box.print-box-cash-out', compact('box'))->setPaper([0, 0, 1385.98, 296.85], 'landscape');
        return $pdf->stream();
    }

    public function deleted()
    {
        return view('backend.store.deleted-box-history');
    }
}
