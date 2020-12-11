<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller {
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct() {
		$this->middleware ( 'auth' );
	}

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Contracts\Support\Renderable
	 */
	public function index() {
		$comms = DB::table ( 'committees' )->get ();
		return view ( 'home', [ 
				'comms' => $comms
		] );
	}
	public function ajax(Request $request) {
// 		return $request;
		
		return DB::table('committee_membership as cm')
		->join('committees as c', 'cm.committee', '=', 'c.id')
		->join('rank as r', 'cm.rank', '=', 'r.id')
		->rightJoin('charge_membership as charge', 'cm.charge_memberhip', '=', 'charge.id')
		->select('cm.*', 'c.committeename as committee',  'r.rank as rank', 'charge.charge_membership as charge')
		->where('charge.committee', '=', $request->cid)
		->get();
		
	}
}
