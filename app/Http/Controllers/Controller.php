<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;

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
		->select ( 'cm.*', 'c.id as committee', 'r.rank', 'charges.charge' )
		->where ( 'chm.committee', '=', $cid )
		->whereNull ( 'chm.deleted_at' )
		->get ();
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
	
	public function getAttributes($eml) {
		$success = false;
		$host = env ( 'ADLDS_HOST' );
		$ldapport = env ( 'ADLDS_PORT' );
		$connection = ldap_connect ( $host, $ldapport );
		$usrbind = 'CN=ITS WDC Service Account,OU=ITS,OU=Service,OU=Users,OU=DAF,OU=Delegated-OUs,DC=campus,DC=ad,DC=csulb,DC=edu';
		$usrpw = env ( 'ADLDS_PW' );
		$search_basedn = "DC=campus,DC=ad,DC=csulb,DC=edu";
		// $search_filter = "(&(UserPrincipalName=$eml))";
		$filter = "(&(objectClass=user)(UserPrincipalName=$eml))";
		$ldapbind = @ldap_bind ( $connection, $usrbind, $usrpw );
		// configure ldap params
		ldap_set_option ( $connection, LDAP_OPT_PROTOCOL_VERSION, 3 );
		ldap_set_option ( $connection, LDAP_OPT_REFERRALS, 0 );
		$entry = ldap_search ( $connection, $search_basedn, $filter );

		if ($entry) {
			$info = @ldap_get_entries ( $connection, $entry );

			$user = User::where ( 'email', '=', $eml )->first ();
			if ($user) {
				Auth::login ( $user );
			} else {
				$user = new User ();
				$user->bid = $info [0] ['employeeid'] [0];
				$user->firstname = $info [0] ['givenname'] [0];
				$user->lastname = $info [0] ['sn'] [0];
				$user->email = $info [0] ['userprincipalname'] [0];
				$user->division = $info [0] ['division'] [0];
				$user->role_id = 2;
				$user->save ();
				Auth::login ( $user );
			}
			$success = true;
		} else {
			$success = false;
		}
		return $success;
	}
}
