<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Committee;
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
		return view ( 'committee' );
	}
	
	public function getComms() {
		return DB::table ( 'committees' )->select ( 'committeename' )->get ();
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
		return view('comm-form');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request) {
		$committees = new Committees();
		$committees->user_id = 0;
		$committees->committeename = $request->commName;
		$committees->meetingtimes_locations = $request->meetTime;
		$committees->notes = $request->notes;
		$committees->save();
		
		return redirect('/committee');
	}
}
