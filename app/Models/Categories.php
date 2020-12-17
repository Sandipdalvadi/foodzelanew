<?php 

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Categories extends Authenticatable
{


	public $timestamps = false;
	protected $table = 'categories';
    protected $primaryKey = 'id';
	
}
