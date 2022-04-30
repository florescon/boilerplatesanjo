<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SessionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.inventories.index');
    }

    public function stock()
    {
        return view('backend.inventories.stock');
    }

    public function feedstock()
    {
        return view('backend.inventories.feedstock');
    }

    public function store()
    {
        return view('backend.inventories.store');
    }
}
