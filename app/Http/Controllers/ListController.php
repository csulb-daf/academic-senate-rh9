<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Community;
use App\Charge;
use App\Rank;

class ListController extends Controller {
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
		// return view('list');
		$comms = DB::table ( 'committees' )->get ();
		return view ( 'list', [ 
				'comms' => $comms
		] );
	}
	
	public function getChargeMembership() {
		return DB::table ( 'charge_membership' )->select ( 'charge_membership' )->get ();
	}
	
	public function getCommunityMembers() {
		return DB::table ( 'community_members' )->select ( DB::raw ( 'CONCAT(firstname, " ", lastname) AS name' ) )->get ();
	}
	
	public function getRank() {
		return DB::table ( 'rank' )->select ( 'rank' )->get ();
	}
	
	public function createCommunity() {
		$charges = DB::table ( 'charge_membership' )->select ( 'id', 'charge_membership' )->get ();
		$comms = DB::table ( 'committees' )->select ( 'id', 'committeename' )->get ();

		return view ( 'community-form', [ 
				'charges' => $charges,
				'comms' => $comms
		] );
	}
	
	public function storeCommunity(Request $request) {
		// return $request->all();
		$validatedData = request ()->validate ( [ 
				'fName' => 'required',
				'lName' => 'required',
				'email' => 'required'
		], [ 
				'fName.required' => 'Please Enter First Name',
				'lName.required' => 'Please Enter Last Name',
				'email.required' => 'Please Enter your email address'
		] );

		if ($validatedData) {
			$community = new Community ();
			$community->user_id = Auth::id ();
			$community->firstname = $request->fName;
			$community->lastname = $request->lName;
			$community->email = $request->email;
			$community->charge_memberhip = $request->charge;
			$community->committee = $request->comms;
			$community->notes = $request->notes;
			$community->save ();

			return redirect ( '/list' );
		} else {
			return redirect ( '/list' )->withInput ()->with ( 'error' );
		}
	}
	
	public function storeCharge(Request $request) {
		//return $request->all();
		$validatedData = request()->validate ( [
				'charge_membership' => 'required',
		], [
				'charge_membership.required' => 'Please Enter Charge Membership',
		] );
		
		if ($validatedData) {
			$charge = new Charge();
			$charge->user_id = Auth::id ();
			$charge->charge_membership = $request->charge_membership;
			$charge->save ();
			
			return redirect()->route('list')->withInput($request->all)->with('message', 'New Charge Membership Added');
		} 
		else {
			return redirect()->route('list')->withInput($request->all)->with('error');
		}
	}
	
	public function storeRank(Request $request) {
// 		return $request->all();
		$validatedData = request()->validate ( [
				'rank' => 'required',
		], [
				'rank.required' => 'Please Enter Rank',
		] );
		
		if ($validatedData) {
			$rank = new Rank();
			$rank->user_id = Auth::id();
			$rank->rank = $request->rank;
			$rank->save ();
			
			return redirect()->route('list')->withInput($request->all)->with('message', 'New Rank Added');
		} 
		else {
			return redirect()->route('list')->withInput($request->all)->with('error');
		}
	}
	
}