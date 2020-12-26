<?php 

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class RestaurentCategory extends Authenticatable
{
    public $timestamps = false;
	protected $table = 'restaurent_category';
    protected $primaryKey = 'id';
    
    public function hasOneCategory()
    {
        return $this->hasOne('App\Models\Categories', 'id', 'category_id');
    }
}
