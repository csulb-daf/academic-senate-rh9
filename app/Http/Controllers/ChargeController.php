<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
// use App\Committees;

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
		return view ( 'charge' );
	}
	
	public function getChargeMemberships() {
		return DB::table( 'charge_membership as chm' )
			->rightJoin('charges as c', 'chm.charge', '=', 'c.id')
			->select('chm.id', 'c.charge as chargeName' )->get();
	}
	
	public function indexMembership() {
		return view('charge-membership');
	}
	
}
