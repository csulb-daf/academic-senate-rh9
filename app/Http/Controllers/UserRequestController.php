<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserRequestController extends Controller
{
	public function index(Request $request)
	{
		return $request->all();
	}
	
}
