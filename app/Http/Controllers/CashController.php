<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cash;

class CashController extends Controller
{
    public function show(Cash $box)
    {
        return view('backend.store.box.show-box', compact('box'));
    }

    public function deleted()
    {
        return view('backend.store.deleted-box-history');
    }
}
