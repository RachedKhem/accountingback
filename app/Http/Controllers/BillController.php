<?php

namespace App\Http\Controllers;

use App\Repositories\BillRepository;
use Illuminate\Http\Request;

class BillController extends CrudController
{
    public function __construct(BillRepository $repository)
    {
        $relations = ['provider', 'payment', 'products'];
        $conditions = ['provision' => true];
        $orderBy = ['date' => 'desc'];
        parent::__construct($repository, $relations, $orderBy, $conditions);
    }

    public function noPayment(Request $request)
    {
        $this->hasNotRelations = ['payment'];
        return parent::index($request);
    }

    public function show($id)
    {
        $this->conditions = [];
        return parent::show($id);
    }
}