<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Provider extends UuidModel
{
    protected $fillable = ['name'];

    public function bills()
    {
        return $this->hasMany(Bill::class);
    }
}
