<?php

namespace App;

class Product extends UuidModel
{
    protected $fillable = [
        'name',
        'category_id',
    ];

    public function __construct(array $attributes = [])
    {
        $this->hidden = array_merge($this->hidden, ['category_id']);
        parent::__construct($attributes);
    }

    /*public function bill()
    {
        return $this->hasOne(Bill::class);
    }*/

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
