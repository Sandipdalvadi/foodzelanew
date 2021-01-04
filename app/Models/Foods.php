<?php 

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Foods extends Authenticatable
{


	public $timestamps = false;
	protected $table = 'foods';
	protected $primaryKey = 'id';
	public function hasOneCategory()
    {
        return $this->hasOne('App\Models\Categories', 'id', 'category_id');
	}
	public function hasOneRestaurent()
    {
        return $this->hasOne('App\Models\Restaurents', 'id', 'category_id');
	}

	public function hasManyFoodsImages()
    {
        return $this->hasMany('App\Models\FoodsImages', 'food_id', 'id');
    }
	
}
