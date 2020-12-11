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
		return DB::table ( 'charge_membership' )->select ( 'charge_membership', 'committee' )->get ();
	}
	
	public function getCommunityMembers() {
		return DB::table ( 'community_members' )->select ( DB::raw ( 'CONCAT(firstname, " ", lastname) AS name' ) )->get ();
	}
	
	public function getRank() {
		return DB::table ( 'rank' )->select('id', 'rank')->get();
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
// 		return $request->all();
		$validatedData = request ()->validate ( [ 
				'fName' => 'required',
				'lName' => 'required',
				'email' => 'required',
				'chargeSelect' => 'required',
				'commSelect' => 'required',
		], [ 
				'fName.required' => 'Please Enter First Name',
				'lName.required' => 'Please Enter Last Name',
				'email.required' => 'Please Enter your email address',
				'chargeSelect.required' => 'Please Select Charge Membership',
				'commSelect.required' => 'Please Select Committee',
		] );

		if ($validatedData) {
			$community = new Community ();
			$community->user_id = Auth::id ();
			$community->firstname = $request->fName;
			$community->lastname = $request->lName;
			$community->email = $request->email;
			$community->charge_memberhip = $request->chargeSelect;
			$community->committee = $request->commSelect;
			$community->notes = $request->notes;
			$community->save ();

			return redirect()->route('list')->withInput($request->all)->with('community', 'New Community Member Added');
		} 
		else {
			return redirect()->route('list')->withInput($request->all)->with('error');
		}
	}
	
	public function createCharge() {
		$charges = DB::table ( 'charge_membership' )->select ( 'id', 'charge_membership' )->get ();
		$comms = DB::table ( 'committees' )->select ( 'id', 'committeename' )->get ();
		
		return view ( 'charge-form', [
				'charges' => $charges,
				'comms' => $comms
		] );
	}
	
	public function storeCharge(Request $request) {
// 		return $request->all();
		$validatedData = request()->validate ( [
				'chargeName' => 'required',
				'commSelect' => 'required',
		], [
				'chargeName.required' => 'Please Enter Charge Membership',
				'commSelect.required' => 'Please Select a Commiittee',
		] );
		
		if ($validatedData) {
			$charge = new Charge();
			$charge->user_id = Auth::id ();
			$charge->charge_membership = $request->chargeName;
			$charge->committee = $request->commSelect;
			$charge->save ();
			
			return redirect()->route('list')->withInput($request->all)->with('charge', 'New Charge Membership Added');
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
			
			return redirect()->route('list')->withInput($request->all)->with('rank', 'New Rank Added');
		} 
		else {
			return redirect()->route('list')->withInput($request->all)->with('error');
		}
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param int $id
	 * @return \Illuminate\Http\Response
	 */
	public function updateRank(Request $request, $id) {
		Rank::where('id', id)
			->update(['rank' => $request->rank]);
		
		return back();
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
		//
	}
}