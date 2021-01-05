<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\TableData;

class HomeController extends Controller {
	use TableData;
	
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
		$comms = DB::table('charge_membership as chm')
		->join('committees as c', 'chm.committee', '=', 'c.id')
		->select('chm.committee as id', 'c.committeename', 'c.meetingtimes_locations', 'c.notes')
		->orderBy('c.committeename', 'asc')
		->groupBy('chm.committee')
		->get();
		
		return view ( 'home', [ 
				'comms' => $comms
		] );
	}

	public function ajax(Request $request) {
		$cid = $request->cid;
		return $this->getCommitteeMemberships($cid);
	}
	
}
