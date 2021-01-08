<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Members;
use App\Traits\TableData;
use App\Charges;
use App\ChargeMembership;
use App\Community;
use App\Committees;

class MembersController extends Controller {
	use TableData;
	
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

	public function ajax($cid) {
		return $this->getCommitteeMemberships($cid);
	}
	
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create($cid, $uid=0) {
		$cname = Committees::where('id', $cid)->pluck('committeename')->first();
		$charges = DB::table('charge_membership as chm')
		->join('charges as c', 'chm.charge', '=', 'c.id')
		->select('c.id', 'c.charge')
		->where('chm.committee', '=', $cid)
		->whereNotIn('chm.charge', DB::table('committee_membership')->pluck('charge'))
		->get();
		
		$ranks = DB::table('rank')->select('id', 'rank')->get();
		$community =  DB::table('community_members')->select('firstname', 'lastname', DB::raw('0 as campus_id'));
		$users = DB::table('sample_directory')
		->select('first_name', 'last_name', 'campus_id')
		->unionAll($community)
		->orderBy('last_name', 'asc')
		->get();

		$formData = Array(
				'charges' => $charges,
				'ranks' => $ranks,
				'cid' => $cid,
				'users' =>$users,
				'cname' => $cname,
				'uid' => $uid
		);
		
		if(!empty($uid)) {
			$row = Members::where('id', $uid)->first();
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
}
