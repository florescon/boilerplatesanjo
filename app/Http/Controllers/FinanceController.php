<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FinanceController extends Controller
{
    public function deleted()
    {
        return view('backend.store.deleted-finances');
    }
}
