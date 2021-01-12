<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\ChargeMembership;
use App\Members;

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
		return view('charge');
	} 
	
	public function displayCommitteeAssignments() {
		return $this->getCommitteeAssignments();
	}
	
	public function getMembership($commID) {
		$comms = DB::table('committees')
		->select('committeename')
		->where('id', '=', $commID)
		->first();
		
		return view('charge-membership', [
			'commID' => $commID,
			'commName' => $comms->committeename,
		]);
	}

	public function getMembershipAjax($commID) {
		return  DB::table( 'charge_membership as chm' )
		->join('charges as c', 'chm.charge', '=', 'c.id')
		->leftJoin('committee_membership as cm', function($join) {
			$join->on('chm.charge', '=', 'cm.charge')->whereNull('cm.deleted_at');
		})
		->select('chm.id', 'chm.charge', 'c.charge as chargeName', DB::raw('concat_ws(" ", cm.firstname, cm.lastname) as assigned_to'))
		->where('chm.committee', '=', $commID)
		->whereNull('chm.deleted_at')
		->get();
// ->toSql();
	}
	
	public function getCharges($commID) {
		return DB::table('charges as c')
		->select('c.id', 'c.charge',
			DB::raw("if(count(chm.charge) > 0, 'yes', 'no') as assigned")
		)
		->leftJoin('charge_membership as chm', function($join) use($commID) {
			$join->on('c.id', '=', 'chm.charge')
				->on('chm.committee', '=', DB::raw($commID))
				->whereNull('chm.deleted_at');
		})
		->groupBy('c.id')
		->orderBy('c.charge', 'asc')
		->get();
// ->toSql();
	}
	
	public function store(Request $request) {
		$charge = new ChargeMembership();
		$charge->user_id = Auth::id();
		$charge->committee = $request->committee;
		$charge->charge = $request->charge;
		$charge->save();
		return $request;
	}
	
	public function update(Request $request) {
// 		Rank::where('id', $request->id)
// 		->update([
// 				'user_id' => Auth::id(),
// 				'rank' => $request->data
// 		]);
		return $request;
	}
	
	public function destroy(Request $request) {
		ChargeMembership::where('id', $request->id)->update(['user_id' => Auth::id()]);
		ChargeMembership::where('id', $request->id)->delete();
		
		if($request->assigned) {
			Members::where('committee', $request->comm)
			->where('charge', $request->charge)
			->update(['user_id' => Auth::id()]);
			Members::where('committee', $request->comm)->where('charge', $request->charge)
			->delete();
		}
		
		return $request;
	}
	
}
