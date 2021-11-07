<?php

namespace App\Repositories;

use App\User;

class UserRepository extends CrudRepository
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function store(array $data)
    {
        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }
        return parent::store($data);
    }

    public function update(array $pks, array $data)
    {
        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }
        return parent::update($pks, $data);
    }
}