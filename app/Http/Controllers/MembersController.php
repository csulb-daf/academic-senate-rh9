<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Members;

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
		return  DB::table('committees')->where('id', '=', $cid)->get();
	}
	
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create($cid) {
		$charges = DB::table('charge_membership')->select('id', 'charge_membership')->get();
		$ranks = DB::table('rank')->select('id', 'rank')->get();
		
		//return $ranks;
		
		return view('member-form', [
				'charges' => $charges,
				'ranks' => $ranks,
				'cid' => $cid,
		]);
		
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request) {
		return $request;
		
		$validatedData = request()->validate(
			[
				'fName' => 'required',
				'lName' => 'required',
				'campusID' => 'required',
				'term' => 'required',
				'charge' => 'required',
				'rank' => 'required',
			],
				
			[
				'fName.required' => 'Please Enter First Name',
				'lName.required' => 'Please Enter Last Name',
				'campusID.required' => 'Please Enter the Campus ID',
				'term.required' => 'Please Select the Term',
				'charge.required' => 'Please Select the Charge Membership',
				'rank.required' => 'Please Select the Rank',
			]
		);
		
		if($validatedData) {
			$members = new Members();
			$members->user_id = Auth::id();;
			$members->committee = $request->committeeID;
			$members->campus_id = $request->campusID;
			$members->lastname = $request->lName;
			$members->firstname = $request->fName;
			$members->rank = $request->rank;
			$members->term = $request->term;
			$members->charge_memberhip = $request->charge;
			$members->notes = $request->notes;
			$members->save();
			
			return redirect('/committee');
		}
		else {
			return redirect('/committee')->withInput()->with('error');
		}
		
	}
}
