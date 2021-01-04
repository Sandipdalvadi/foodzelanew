<?php 

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class FoodsImages extends Authenticatable
{


	public $timestamps = false;
	protected $table = 'foods_images';
	protected $primaryKey = 'id';
}
