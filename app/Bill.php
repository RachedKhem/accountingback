<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bill extends UuidModel
{
    protected $fillable = [
        'date',
        'tax_stamp',
        'provider_id',
        'provision',
        'deadline'
    ];

//    protected $casts = [
//        'date' => 'datetime',
//        'deadline' => 'date',
//    ];

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)
            ->as('purchase')
            ->withPivot(['quantity', 'price']);
    }
}
