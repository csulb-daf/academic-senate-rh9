<?php

namespace App\Traits;
use Illuminate\Support\Facades\DB;

trait TableData {
	public function getCommitteeMemberships($cid) {
		
		return DB::table('committee_membership as cm')
		->join('committees as c', 'cm.committee', '=', 'c.id')
		->join('rank as r', 'cm.rank', '=', 'r.id')
		->rightJoin('charge_membership as chm', 'cm.charge', '=', 'chm.charge')
		->join('charges', 'charges.id', '=', 'chm.charge')
		->select('cm.*', 'c.id as committee',  'r.rank as rank', 'charges.charge')
		->where('chm.committee', '=', $cid)
		->get();
// 		->toSql();
	}
	
	public function getCommitteeAssignments() {
		return DB::table('committees as c')
		->select('c.id', 'c.committeename as comm', DB::raw('count(cm.committee) as assignments'))
		->leftJoin('charge_membership as cm', 'c.id', '=', 'cm.committee')
		->groupBy('c.id')
		->get();
// ->toSql();
	}
	
}