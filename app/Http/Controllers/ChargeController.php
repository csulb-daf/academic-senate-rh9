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
		return DB::table('committees')->select('id', 'committeename as comm')->get();
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
	
	public function getCharges() {
		return DB::table('charges')
		->select('id', 'charge')
		->orderBy('charge', 'asc')
		->get();
	}
	
	public function store(Request $request) {
// 		return $request;
		$validatedData = request()->validate ( [
				'committee' => 'required',
		], [
				'committee.required' => 'Please Enter Committee',
		] );

		if ($validatedData) {
			$charge = new ChargeMembership();
			$charge->user_id = Auth::id();
			$charge->committee = $request->committee;
			$charge->charge = $request->charge;
// 			$charge->save();
			
			return response()->json([
				'message' => 'New Charge Added',
			]);
		}
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
