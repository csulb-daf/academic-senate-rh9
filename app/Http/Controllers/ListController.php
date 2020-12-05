<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\AssignOp\Concat;

class ListController extends Controller
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
        //return view('list');
        
			$comms = DB::table('committees')->get();
			return view('list', ['comms' => $comms]);
        
    }
    
    public function getChargeMembership()
    {
    	return  DB::table('charge_membership')->select('charge_membership')->get();
    }
    
    public function getCommunityMembers()
    {
    	return  DB::table('community_members')->select(DB::raw('CONCAT(firstname, " ", lastname) AS name'))->get();
    }
    
    public function getRank()
    {
    	return  DB::table('rank')->select('rank')->get();
    }
    
}
