<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Community;
use App\Charges;
use App\Rank;
use App\Committees;

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
		$communityComms = DB::table('committees as c')
		->join('community_members as cm', 'c.id', '=', 'cm.committee')
		->select('c.*')
		->orderBy('c.committeename', 'asc')
		->distinct()
		->get();
		
		return view ( 'list', [
			'communityComms' => $communityComms
		] );
	}
	
	/*** Commmunity Members ***/
	public function getCommunityMembers(Request $request) {
		return DB::table('community_members')
		->select('id', DB::raw('CONCAT(firstname, " ", lastname) AS name'))
		->where('committee', '=', $request->id)
		->get();
	}
	
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function createCommunity() {
		$charges = Charges::all( 'id', 'charge' );
		$comms = Committees::all( 'id', 'committeename' );

		return view ( 'community-form', [ 
				'charges' => $charges,
				'comms' => $comms
		] );
	}
	
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function storeCommunity(Request $request) {
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
	
	/**
	 * Update the specified resource in storage.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param int $id
	 * @return \Illuminate\Http\Response
	 */
	public function updateCommunity(Request $request) {
		$nameArr = explode(' ', $request->data);
		Community::where('id', $request->id)
		->update([
				'user_id' => Auth::id(),
				'firstname' => $nameArr[0],
				'lastname' => $nameArr[1],
		]);
		
		return $request;
	}
	
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroyCommunity(Request $request) {
		Community::where('id', $request->id)->delete();
		return $request;
	}
	
	/*** Charge Membership ***/
	public function getCharges() {
		return Charges::all('id', 'charge');
	}
	
	public function storeCharge(Request $request) {
		$validatedData = request()->validate ( [
				'chargeName' => 'required',
		], [
				'chargeName.required' => 'Please Enter Charge Membership',
		] );
		
		if ($validatedData) {
			$charges = new Charges();
			$charges->user_id = Auth::id ();
			$charges->charge = $request->chargeName;
			$charges->save();
			
			return redirect()->route('list')->withInput($request->all)->with('charge', 'New Charge Membership Added');
		} 
		else {
			return redirect()->route('list')->withInput($request->all)->with('error');
		}
	}
	
	public function updateCharge(Request $request) {
		Charges::where('id', $request->id)
		->update([
				'user_id' => Auth::id(),
				'charge' => $request->data,
		]);
		
		return $request;
	}

	public function destroyCharge(Request $request) {
		Charges::where('id', $request->id)->delete();
		return $request;
	}
	
	/*** Rank ***/
	public function getRank() {
		return Rank::all('id', 'rank');
	}
	
	public function storeRank(Request $request) {
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

	public function updateRank(Request $request) {
		Rank::where('id', $request->id)
		->update([
				'user_id' => Auth::id(),
				'rank' => $request->data
		]);
		return $request;
	}

	public function destroyRank(Request $request) {
		Rank::where('id', $request->id)->delete();
		return $request;
	}

}