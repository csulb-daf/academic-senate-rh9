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
		$comms = DB::table('charge_membership as chm')
		->join('committees as c', 'chm.committee', '=', 'c.id')
		->select('chm.committee as id', 'c.committeename', 'c.meetingtimes_locations', 'c.notes')
		->orderBy('c.committeename', 'asc')
		->groupBy('chm.committee')
		->get();
		
		$community =  DB::table('community_members')->select('firstname', 'lastname', DB::raw('0 as campus_id'));
		$users = DB::table('sample_directory')
		->select('first_name', 'last_name', 'campus_id')
		->unionAll($community)
		->orderBy('last_name', 'asc')
		->get();
		
		
		return view ( 'home', [ 
				'comms' => $comms,
				'users' => $users,
		] );
	}

	public function ajax(Request $request) {
		$cid = $request->cid;
		return $this->getCommitteeMemberships($cid);
	}
	
	public function memberSearch(Request $request) {
// 		return $request;
		return view('member-search', [
// 				'comms' => $comms,
// 				'users' => $users,
		]);
		
	}
	
}
