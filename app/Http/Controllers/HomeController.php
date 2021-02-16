<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Members;
use App\Committees;

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
		return view('home');
	}

	public function getCommittees(Request $request) {
		/* Ignore whitespaces */
		if(empty($request->q) || (strlen($request->q) < $request->min)) {
			return null;	
		}
		
		return DB::table('charge_membership as chm')
		->join('committees as c', 'chm.committee', '=', 'c.id')
		->select('chm.committee', 'c.committeename')
		->where('c.committeename', 'like', "%$request->q%")
		->orderBy('c.committeename', 'asc')
		->groupBy('chm.committee')
		->get();
	}
	
	public function committeeSearch(Request $request) {
		$cid = $request->cid;
		return $this->getCommitteeMemberships($cid);
	}

	public function getMembers(Request $request) {
		/* Ignore whitespaces */
		if(empty($request->q) || (strlen($request->q) < $request->min)) {
			return null;
		}
		
		return Members::distinct()
		->where(DB::raw("CONCAT_WS(' ', firstname, lastname)"), 'like',  DB::raw("REPLACE('%$request->q%', ' ', '%')" ))
		->orWhere(DB::raw("CONCAT_WS(' ',lastname, firstname)"), 'like',  DB::raw("REPLACE('%$request->q%', ' ', '%')" ))
		->select('campus_id', 'college', DB::raw("CONCAT_WS(', ', lastname, firstname) AS name"))
		->orderBy('name')
		->get();
	}
	
	public function memberSearch(Request $request) {
		$sql =  DB::Table('committee_membership as cm')
		->join('committees as c', 'cm.committee', '=', 'c.id')
		->join('charges as ch', 'cm.charge', '=', 'ch.id')
		->join('rank as r', 'cm.rank', '=', 'r.id')
		->select('cm.*', 'c.id as committee', 'c.committeename',  'r.rank', 'ch.charge')
		->whereNull('cm.deleted_at');

		if($request->campus_id === 0) {
			$sql->where('firstname', "$request->firstname");
			$sql->where('lastname', "$request->lastname");
		}
		else {
			$sql->where('campus_id', "$request->memberSelect");
		}

		return $sql->get();
	}

}

