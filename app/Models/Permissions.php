<?php 

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Permissions extends Authenticatable
{


	public $timestamps = false;
	protected $table = 'permissions';
    protected $primaryKey = 'id';
	
}
