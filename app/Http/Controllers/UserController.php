<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;

class UserController extends CrudController
{
    public function __construct(UserRepository $repository)
    {
        $relations = ['role'];
        parent::__construct($repository, $relations);
    }
}