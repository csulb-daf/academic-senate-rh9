<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
			$comms = DB::table('committees')->get();
			return view('home', ['comms' => $comms]);
    }
    
    public function ajax()
    {
    	return DB::table('committee_membership as cm')
    		->join('rank as r', 'cm.rank', '=', 'r.id')
    		->select('cm.*', 'r.rank as rank')
    		->get();
    }
    
}
