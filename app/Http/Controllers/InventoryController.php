<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory;

class InventoryController extends Controller
{
    public function showStore(Inventory $inventory)
    {
        return view('backend.inventories.store.store-show', compact('inventory'));
    }

    public function showStock(Inventory $inventory)
    {
        return view('backend.inventories.stock.stock-show', compact('inventory'));
    }
}
