<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use App\Members;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    public function getCommitteeMemberships($cid) {
			//TODO: rewrite these in Eloquent to automatically filter out soft deletes    	
    	return DB::table('committee_membership as cm')
    	->join('committees as c', 'cm.committee', '=', 'c.id')
    	->join('rank as r', 'cm.rank', '=', 'r.id')
    	->rightJoin('charge_membership as chm', function($join) {
    		$join->on('cm.charge', '=', 'chm.charge')->whereNull('cm.deleted_at');
    	})
    	->join('charges', 'charges.id', '=', 'chm.charge')
    	->select('cm.*', 'c.id as committee',  'r.rank as rank', 'charges.charge')
    	->where('chm.committee', '=', $cid)
    	->whereNull('chm.deleted_at')
    	->get();
// 	->toSql();
    }
    
    public function getCommitteeAssignments() {
    	//TODO: rewrite these in Eloquent to automatically filter out soft deletes
    	return DB::table('committees as c')
    	->select('c.id', 'c.committeename as comm', DB::raw('count(cm.committee) as assignments'))
    	->leftJoin('charge_membership as cm', 'c.id', '=', 'cm.committee')
    	->whereNull('cm.deleted_at')
    	->groupBy('c.id')
    	->get();
//     	->toSql();
    }
    
}
