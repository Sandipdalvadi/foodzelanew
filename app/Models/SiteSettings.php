<?php 

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class SiteSettings extends Authenticatable
{


	public $timestamps = false;
	protected $table = 'site_settings';
    protected $primaryKey = 'id';
	
}
