<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Members;
use App\Charges;
use App\Committees;
use App\Community;
use App\Employees;

class MembersController extends Controller {
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
	public function index($cid) {
		//$request = Committees::all('committeename');
		$request = DB::table('committees')
		->select('committeename')
		->where('id', '=', $cid)
		->first();
		
		return view('members', [
				'cid' => $cid, 
				'cname' => $request->committeename,
		]);
	}

	public function getMemberships($cid) {
		return $this->getCommitteeMemberships($cid);
	}
	
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create($cid, $mid=0) {
		//TODO: rewrite query in Eloquent to automatically filter out soft deletes
		$cname = Committees::where('id', $cid)->pluck('committeename')->first();
		$charges = DB::table('charge_membership as chm')
		->join('charges as c', 'chm.charge', '=', 'c.id')
		->select('c.id', 'c.charge')
		->where('chm.committee', '=', $cid)
		->whereNotIn('chm.charge', Members::where('committee', $cid)->pluck('charge'))
		->whereNull('chm.deleted_at')
		->get();
		
		$ranks = DB::table('rank')->select('id', 'rank')->get();
		$community = Community::all('firstname as first_name', 'lastname as last_name', DB::raw('0 as campus_id'));
		$employees = Employees::all('first_name', 'last_name', 'campus_id');
		$users = $employees->mergeRecursive($community)->sortBy('last_name');

		$formData = Array(
				'charges' => $charges,
				'ranks' => $ranks,
				'cid' => $cid,
				'users' =>$users,
				'cname' => $cname,
				'mid' => $mid,
		);
		
		if(!empty($mid)) {
			$row = Members::where('id', $mid)->first();
			$chargeName = Charges::where('id', $row->charge)->pluck('charge')->first();
			$formData = array_merge($formData,[
				'fname' => $row->firstname,
				'lname' => $row->lastname,
				'campusID' => $row->campus_id,
				'notes' => $row->notes,
				'termID' => $row->term,
				'chargeID' => $row->charge,
				'chargeName' => $chargeName,
				'rankID' => $row->rank,
				'alternate' => $row->alternate,
			]);
		}
		return view('member-form', $formData);
	}
	
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request) {
// 		return $request;
		
		$validatedData = request()->validate(
			[
				'fName' => 'required',
				'lName' => 'required',
				'campusID' => 'required',
				'termSelect' => 'required',
				'chargeSelect' => 'required',
				'rankSelect' => 'required',
			],
				
			[
				'fName.required' => 'Please Enter First Name',
				'lName.required' => 'Please Enter Last Name',
				'campusID.required' => 'Please Enter the Campus ID',
				'termSelect.required' => 'Please Select the Term',
				'chargeSelect.required' => 'Please Select the Charge Membership',
				'rankSelect.required' => 'Please Select the Rank',
			]
		);
		
		if($validatedData) {
			$members = new Members();
			$members->user_id = Auth::id();
			$members->committee = $request->cid;
			$members->campus_id = $request->campusID;
			$members->lastname = $request->lName;
			$members->firstname = $request->fName;
			$members->rank = $request->rankSelect;
			$members->term = $request->termSelect;
			$members->charge = $request->chargeSelect;
			if(isset($request->alternate)) { $members->alternate =$request->alternate; }
			$members->notes = $request->notes;
			$members->save();
			
			return redirect()->route('comm.assign', ['cid'=>$request->cid])->withInput($request->all)->with('member', 'New Committee Member Added');
		}
		else {
			return redirect()->route('committee')->withInput($request->all)->with('error');
		}
	}
	
	public function update(Request $request) {
		$validatedData = request()->validate(
			[
					'fName' => 'required',
					'lName' => 'required',
					'campusID' => 'required',
					'termSelect' => 'required',
					'chargeSelect' => 'required',
					'rankSelect' => 'required',
			],
			
			[
					'fName.required' => 'Please Enter First Name',
					'lName.required' => 'Please Enter Last Name',
					'campusID.required' => 'Please Enter the Campus ID',
					'termSelect.required' => 'Please Select the Term',
					'chargeSelect.required' => 'Please Select the Charge Membership',
					'rankSelect.required' => 'Please Select the Rank',
			]
			);
		
		if($validatedData) {
			Members::where('id', $request->mid)
			->update([
				'user_id' => Auth::id(),
				'firstname' => $request->fName,
				'lastname' => $request->lName,
				'campus_id' => $request->campusID,
				'term' => $request->termSelect,
				'charge' => $request->chargeSelect,
				'rank' => $request->rankSelect,
				'notes' => $request->notes,
				'alternate' => isset($request->alternate)? $request->alternate:0,
		]);
		return redirect()->route('comm.assign', ['cid'=>$request->cid])->withInput($request->all)->with('member', 'Committee Member Updated Successfully');
		}
		else {
			return back()->withInput($request->all)->with('error');
		}
		
	}
	
	public function destroy(Request $request) {
		Members::where('id', $request->id)->update(['user_id' => Auth::id()]);
		Members::where('id', $request->id)->delete();
		return $request;
	}
}
