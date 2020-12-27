<?php 

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Restaurents extends Authenticatable
{


	protected $table = 'restaurents';
    protected $primaryKey = 'id';
	public function hasOneUser()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }
}
