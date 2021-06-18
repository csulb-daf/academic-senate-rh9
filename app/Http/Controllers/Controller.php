<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class Controller extends BaseController {
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
	public function getCommitteeMemberships($cid) {
		// TODO: rewrite these in Eloquent to automatically filter out soft deletes
		return DB::table ( 'committee_membership as cm' )
		->join ( 'committees as c', 'cm.committee', '=', 'c.id' )
		->join ( 'rank as r', 'cm.rank', '=', 'r.id' )
		->rightJoin ( 'charge_membership as chm', function ($join) {
			$join->on ( 'cm.charge', '=', 'chm.charge' )
			->on ( 'cm.committee', '=', 'chm.committee' )
			->whereNull ( 'cm.deleted_at' );
		})
		->join ( 'charges', 'charges.id', '=', 'chm.charge' )
		->select ( 'cm.*', 'c.id as committee', 'r.rank', 'charges.id as chargeID', 'charges.charge' )
		->where ( 'chm.committee', '=', $cid )
		->whereNull ( 'chm.deleted_at' )
		->get();
	}
	
	public function getCommitteeChargeCount() {
		// TODO: rewrite these in Eloquent to automatically filter out soft deletes
		return DB::table ( 'committees as c' )
		->select ('c.id', 'c.committeename as comm', DB::raw ( 'count(cm.committee) as assignments'))
		->leftJoin ( 'charge_membership as cm', 'c.id', '=', 'cm.committee' )
		->whereNull ( 'cm.deleted_at' )
		->whereNull ( 'c.deleted_at' )
		->groupBy ( 'c.id' )
		->get ();
	}
	
	public function directorySearch(Request $request) {
		$host = env('ADLDS_HOST');
		$ldapport = env('ADLDS_PORT');
		$connection = ldap_connect($host, $ldapport);
		$usrbind = 'CN=ITS WDC Service Account,OU=ITS,OU=Service,OU=Users,OU=DAF,OU=Delegated-OUs,DC=campus,DC=ad,DC=csulb,DC=edu';
		$usrpw = env('ADLDS_PW');
		$search_basedn = "DC=campus,DC=ad,DC=csulb,DC=edu";
		@ldap_bind($connection, $usrbind, $usrpw);

		// configure ldap params
		ldap_set_option($connection, LDAP_OPT_PROTOCOL_VERSION, 3);
		ldap_set_option($connection, LDAP_OPT_REFERRALS, 0);
		$filter = "(&(objectClass=user)(|(extensionattribute11=Active)(extensionattribute11=Unassigned)(extensionattribute11=Leave)(extensionattribute11=Leave with Pay)(extensionattribute11=Short Work Break))(|(sn=$request->q*)(givenname=$request->q*)(displayname=$request->q*)))";
		$attributes = array('employeeid', 'givenname', 'sn', 'department', 'division', 'mail', 'telephonenumber', 'employeetype');
		$entry = ldap_search($connection, $search_basedn, $filter,  $attributes);
		
		if(empty($entry)) {
			return null;
		}
		
		$info = @ldap_get_entries($connection, $entry);
		//print_r($info);
		
		$employees = array();
		foreach($info as $key => $val) {
			if(!empty($val['employeeid'][0])) {
				$employees[$key]['campus_id'] = $val['employeeid'][0];
				$employees[$key]['name'] = $val['sn'][0] .', '. $val['givenname'][0];
				$employees[$key]['department'] = !empty($val['department'][0])? $val['department'][0]:'';
				$employees[$key]['college_department'] = !empty($val['division'][0])? $val['division'][0]:'';
				$employees[$key]['extension'] = !empty($val['telephonenumber'][0])? $val['telephonenumber'][0]:'';
				$employees[$key]['email'] =  !empty($val['mail'][0])? $val['mail'][0]:'';
				$employees[$key]['employeetype'] =  !empty($val['employeetype'][0])? $this->mapEmployeeType($val['employeetype'][0]):'';
				
			}
		}
		return array_values($employees);
	}

	/**
	 * 
	 * @param String $str
	 * maps employee type and sort order
	 * returns array
	 */
	function mapEmployeeType($str) {
		$empType = [];
		
		switch($str) {
			case 'Auxiliary-SA':
			case 'SA':
			case 'TA':
			case 'Former Student':
			case 'Student':
				$empType['sortOrder'] = 10;
				$empType['name'] = 'Student';
				break;

			case 'FAC-Lecturer\Temporary':
			case 'FAC-Tenure\Tenure Track':
				$empType['sortOrder'] = 20;
				$empType['name'] = 'Faculty';
				break;
				
			case 'STF':
			case 'Auxiliary-STF':
			case 'MPP':
				$empType['sortOrder'] = 30;
				$empType['name'] = 'Staff';
				break;
				
			default:
				$empType['sortOrder'] = 100;
				$empType['name'] = $str;
		}
		
		return $empType;
	}
}
