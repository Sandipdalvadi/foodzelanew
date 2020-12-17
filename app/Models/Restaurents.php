<?php 

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Restaurents extends Authenticatable
{


	public $timestamps = false;
	protected $table = 'restaurents';
    protected $primaryKey = 'id';
	
}
