<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends UuidModel
{
    protected $fillable = ['name'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
