<?php
/**
 * Created by PhpStorm.
 * User: khlil
 * Date: 14/09/2019
 * Time: 6:28 PM
 */
namespace App;

use Webpatser\Uuid\Uuid;
trait Uuids
{

    /**
     * Boot function from laravel.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = Uuid::generate()->string;
        });
    }
}
