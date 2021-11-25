<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class CartController extends Controller
{

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$fromStore = false; // string
	    return view('backend.cart.index', compact('fromStore'));
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function from_store()
	{
		$fromStore = true; // string
	    return view('backend.cart.index', compact('fromStore'));
	}

}
