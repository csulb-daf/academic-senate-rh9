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
		return DB::table('committee_membership as cm')
		->join('committees as c', 'cm.committee', '=', 'c.id')
		->join('rank as r', 'cm.rank', '=', 'r.id')
		->rightJoin('charge_membership as chm', 'cm.charge', '=', 'chm.charge')
		->join('charges', 'charges.id', '=', 'chm.charge')
		->select('cm.*', 'c.id as committee',  'r.rank as rank', 'charges.charge')
		->where('chm.committee', '=', $request->cid)
		->get();
	}
}
