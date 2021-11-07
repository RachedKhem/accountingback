<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends UuidModel
{
    protected $fillable = [
        'check_number',
        'date',
        'bill_id'
    ];

    public function bill()
    {
        return $this->belongsTo(Bill::class);
    }
}
