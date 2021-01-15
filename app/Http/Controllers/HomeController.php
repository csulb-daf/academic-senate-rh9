<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Members;

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
		
		$users = Members::distinct()
		->select('firstname as first_name', 'lastname as last_name', 'campus_id')
		->orderBy('last_name')
		->get();
// 		return $users;
		
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
				'campusID' => $request->userSelect,
				'firstName' => $request->firstname,
				'lastName' => $request->lastname,
		]);
	}
	
	public function memberSearchResult(Request $request) {
		return $request;
		$sql =  DB::Table('committee_membership as cm')
		->join('committees as c', 'cm.committee', '=', 'c.id')
		->join('charges as ch', 'cm.charge', '=', 'ch.id')
		->join('rank as r', 'cm.rank', '=', 'r.id')
		->select('cm.*', 'c.committeename', 'ch.charge as chargeName', 'r.rank as rankName')
		->whereNull('cm.deleted_at');
		
		if($request->campus_id === 0) {
			$sql->where('firstname', "$request->first_name");
			$sql->where('lastname', "$request->last_name");
		}
		else {
			$sql->where('campus_id', "$request->campus_id");
		}
		
		return $sql->get();
	}
	
}
