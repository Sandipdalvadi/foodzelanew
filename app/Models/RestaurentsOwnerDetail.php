<?php 

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class RestaurentsOwnerDetail extends Authenticatable
{


	public $timestamps = false;
	protected $table = 'restaurent_owner_detail';
    protected $primaryKey = 'id';
	
}
