<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'category';

    protected $primaryKey = 'id_cate';

    public $timestamps = false;

    protected $fillable = ['name_cate'];

    public function products()
    {
        return $this->hasMany(Product::class, 'idcate', 'id_cate');
    }
}
