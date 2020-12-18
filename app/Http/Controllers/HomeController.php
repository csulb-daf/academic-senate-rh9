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
		$comms = DB::table('committees as c')
		->join('committee_membership as cm', 'c.id', '=', 'cm.committee')
		->select('c.*')
		->distinct()
		->orderBy('c.committeename', 'asc')
		->get();
		return view ( 'home', [ 
				'comms' => $comms
		] );
	}

	public function ajax(Request $request) {
		$cid = $request->cid;
		return $this->getCommitteeData($cid);
	}
	
}
