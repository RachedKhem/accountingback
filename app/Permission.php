<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends UuidModel
{
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
