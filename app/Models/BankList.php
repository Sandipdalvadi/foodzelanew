<?php 

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class BankList extends Authenticatable
{


	public $timestamps = false;
	protected $table = 'bank_list';
    protected $primaryKey = 'id';
	
}
