<?php

namespace App\Http\Controllers;

use App\Models\Cloth;
use Illuminate\Http\Request;

class ClothController extends Controller
{

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
	    return view('backend.cloth.index');
	}


	public function deleted()
	{
	    return view('backend.cloth.deleted');
	}

}
