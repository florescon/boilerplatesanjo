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
        $pdf = PDF::loadView('backend.store.box.print-box', compact('box'))->setPaper([0, 0, 4385.98, 296.85], 'landscape');
        return $pdf->stream();
    }

    public function voucherCash(Cash $box)
    {
        $pdf = PDF::loadView('backend.store.box.voucher-cash', compact('box'))->setPaper([0, 0, 438.98, 296.85], 'landscape');
        return $pdf->stream();
    }

    public function ticketCash(Cash $box)
    {
        $box->load(['finances' => function ($query) {
               $query->where('payment_method_id', 1);
            }]
        );

        $pdf = PDF::loadView('backend.store.box.print-box-cash', compact('box'))->setPaper([0, 0, 4385.98, 296.85], 'landscape');
        return $pdf->stream();
    }

    public function ticketCashOut(Cash $box)
    {
        $box->load(['finances' => function ($query) {
               $query->where('payment_method_id', '!=', 1);
            }]
        );

        $pdf = PDF::loadView('backend.store.box.print-box-cash-out', compact('box'))->setPaper([0, 0, 4385.98, 296.85], 'landscape');
        return $pdf->stream();
    }

    public function print(Cash $box)
    {
        return view('backend.store.box.print', compact('box'));
    }

    public function printexport(?string $boxes = null)
    {
        $json_decode = json_decode($boxes);

        if($boxes)
            $cashes = Cash::whereIn('id', $json_decode)
                    ->with('finances', 'orders')
                    ->get();
        else
            $cashes = null;

        $totalIncomes = 0;
        $totalExpenses = 0;
        $totalCash = 0;
        $totalAnotherPayment = 0;

        foreach($cashes as $cash){
            foreach($cash->finances as $finance ){
                if($finance->isIncome()){
                    if($finance->payment_method_id === 1){
                        $totalCash += $finance->amount;
                    }
                    else{
                        $totalAnotherPayment += $finance->amount;
                    }
                    $totalIncomes += $finance->amount;
                }

                if($finance->isExpense())
                    $totalExpenses += $finance->amount;
            }            
        }

        return view('backend.store.box.print-export-index', [
            'boxes' => $cashes,
            'json_decode' => $json_decode,
            'totalIncomes' => $totalIncomes ?? 0,
            'totalExpenses' => $totalExpenses ?? 0,
            'totalCash' => $totalCash ?? 0,
            'totalAnotherPayment' => $totalAnotherPayment ?? 0,
        ]);
    }

    public function deleted()
    {
        return view('backend.store.deleted-box-history');
    }
}
