<?php

namespace App\Http\Controllers;

use App\Depot;

use App\Repositories\DepotRepository;
use Illuminate\Http\Request;

class DepotController extends CrudController
{
    public function __construct(DepotRepository $repository)
    {
        $orderBy = ['date' => 'desc'];
        parent::__construct($repository, [], $orderBy);
    }

    public function box(Request $request)
    {
        $this->conditions = ['type' => false];
        return parent::index($request);
    }

    public function bank(Request $request)
    {
        $this->conditions = ['type' => true];
        return parent::index($request);
    }
}
