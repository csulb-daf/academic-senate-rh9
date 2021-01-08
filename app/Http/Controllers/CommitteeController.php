<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Committees;

class CommitteeController extends Controller {
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
		return view('committee');
	}
	
	public function displayCommitteeAssignments() {
		return $this->getCommitteeAssignments();
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
		return view('committee-form');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request) {
		
		$validatedData = request()->validate(
			[
				'commName' => 'required',
				'meetTime' => 'required',
			],
				
			[
				'commName.required' => 'Please Enter the Committee Name',
				'meetTime.required' => 'Please Enter the Meeting Time and Location',
			]
		);
		
		if($validatedData) {
			$committees = new Committees();
			$committees->user_id =  Auth::id();
			$committees->committeename = $request->commName;
			$committees->meetingtimes_locations = $request->meetTime;
			$committees->notes = $request->notes;
			$committees->save();
			
			return redirect()->route('committee')->withInput($request->all)->with('committee', 'New Committee Added');
		}
		else {
			return back()->withInput($request->all)->with('error');
		}
		
	}
}
