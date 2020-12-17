<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Members;
use phpDocumentor\Reflection\Types\Object_;

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
		return view('members', ['cid' => $cid]);
	}

	public function ajax($cid) {
		return DB::table('committee_membership as cm')
		->join('committees as c', 'cm.committee', '=', 'c.id')
		->join('rank as r', 'cm.rank', '=', 'r.id')
		->rightJoin('charge_membership as charge', 'cm.charge_memberhip', '=', 'charge.id')
		->select('cm.*', 'c.committeename as committee',  'r.rank as rank', 'charge.charge_membership as charge')
		->where('charge.committee', '=', $cid)
		->get();
	}
	
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create($cid) {
		//$charges = DB::table('charge_membership')->select('id', 'charge_membership')->get();
		
		$charges = DB::table('charge_membership')
		//->join('committee_membership as cm', 'cm.id', '=', 'charge.committee')
		->select('id', 'charge_membership')
		->where('committee', '=', $cid)
		->orderBy('charge_membership', 'asc')
		//->distinct()
		->get();
		
		$ranks = DB::table('rank')->select('id', 'rank')->get();
		
		$users[0] = array(
				'fname' => 'Inigo',
				'lname' => 'Montoya',
				'campus_id' => '1234566789'
		);
		$users[1] = array(
				'fname' => 'Ismael',
				'lname' => 'Morales',
				'campus_id' => '2468101214'
		);
		
		$userObj = (object)$users;
		
		return view('member-form', [
				'charges' => $charges,
				'ranks' => $ranks,
				'cid' => $cid,
				'users' =>$userObj,
		]);
		
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
			$members->charge_memberhip = $request->chargeSelect;
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
