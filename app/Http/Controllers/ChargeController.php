<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\ChargeMembership;

class ChargeController extends Controller {
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
		return view('charge');
	} 
	
	public function getComms() {
		return DB::table('committees as c')
		->select('c.id', 'c.committeename as comm', DB::raw('count(cm.committee) as assignments'))
		->leftJoin('charge_membership as cm', 'c.id', '=', 'cm.committee')
		->groupBy('c.id')
		->get();
	}
	
	public function getMembership($commID) {
		$comms = DB::table('committees')
		->select('committeename')
		->where('id', '=', $commID)
		->first();
		
		return view('charge-membership', [
			'commID' => $commID,
			'commName' => $comms->committeename,
		]);
	}

	public function getMembershipAjax($commID) {
		return  DB::table( 'charge_membership as chm' )
		->join('charges as c', 'chm.charge', '=', 'c.id')
		->select('chm.charge', 'c.charge as chargeName' )
		->where('chm.committee', '=', $commID)
		->get();
	}
	
	public function getCharges($commID) {
		return DB::table('charges as c')
		->select('c.id', 'c.charge',
			DB::raw("if(cm.charge is not null and cm.committee = ". $commID .", 'yes', 'no') as assigned")
		)
		->leftJoin('charge_membership as cm', 'c.id', '=', 'cm.charge')
		->groupBy('c.id')
		->orderBy('c.charge', 'asc')
		->get();
// ->toSql();
	}
	
	public function store(Request $request) {
		$charge = new ChargeMembership();
		$charge->user_id = Auth::id();
		$charge->committee = $request->committee;
		$charge->charge = $request->charge;
		$charge->save();
	}
	
	public function update(Request $request) {
		Rank::where('id', $request->id)
		->update([
				'user_id' => Auth::id(),
				'rank' => $request->data
		]);
		return $request;
	}
	
	public function destroy(Request $request) {
		Rank::where('id', $request->id)
		->update([	'active' => 0]);
		return $request;
	}
	
}
