<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CashController extends Controller
{
    public function deleted()
    {
        return view('backend.store.deleted-box-history');
    }
}
