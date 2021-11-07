<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends UuidModel
{
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function users()
    {
        return $this->hasMany(Role::class);
    }
}
