<?php

namespace App\Http\Controllers;

use App\Repositories\ProviderRepository;

class ProviderController extends CrudController
{
    public function __construct(ProviderRepository $repository)
    {
        $orderBy = ['name' => 'asc'];
        parent::__construct($repository, [], $orderBy);
    }
}