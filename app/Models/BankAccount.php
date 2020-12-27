<?php 

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class BankAccount extends Authenticatable
{


	public $timestamps = false;
	protected $table = 'bank_account';
	protected $primaryKey = 'id';
	public function hasOneBankList()
    {
        return $this->hasOne('App\Models\BankList', 'id', 'bank_id');
    }
	
}
