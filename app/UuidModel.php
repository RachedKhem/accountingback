<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

abstract class UuidModel extends Model
{
    use Uuids;

    public $incrementing = false;

    protected $hidden = [
        'updated_at',
        'created_at'
    ];
}
