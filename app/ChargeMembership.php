<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChargeMembership extends Model {
	protected $table = 'charge_membership';
	use SoftDeletes;
}
