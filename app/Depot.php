<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Depot extends UuidModel
{
    protected $fillable = [
        'amount',
        'type',
        'date'
    ];
}
