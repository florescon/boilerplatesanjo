<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory;

class InventoryController extends Controller
{
    public function show(Inventory $inventory)
    {
        return view('backend.inventories.store.store-show', compact('inventory'));
    }
}
