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
	
	public function getCharges() {
		return DB::table('charges')->get();
	}
	
	public function getMembership($chargeID) {
		$comms = DB::table('committees')
		->select('id', 'committeename')
		->orderBy('committeename', 'asc')
		->get();
		
		$request = DB::table('charges')
		->select('charge')
		->where('id', '=', $chargeID)
		->first();
		
		return view('charge-membership', [
			'id' => $chargeID,
			'chargeName' => $request->charge,
			'comms' => $comms,
		]);
	}
	
	public function getMembershipAjax($chargeID) {
		return  DB::table( 'charge_membership as chm' )
		->join('committees as c', 'chm.committee', '=', 'c.id')
		->select('c.committeename as commName' )
		->where('chm.charge', '=', $chargeID)
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
// 			$charge->save();
			
			//return back()->withInput($request->all)->with('committee', 'New Committee Added');
			return response()->json([
				'message' => 'New Committee Added',
				'commName' => $request->commName,
			]);
		}
// 		else {
// 			return redirect()->route('charge.assign')->withInput($request->all)->with('error');
// 		}
	}
	
}
