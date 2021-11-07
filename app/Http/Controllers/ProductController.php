<?php

namespace App\Http\Controllers;

use App\Repositories\ProductRepository;

class ProductController extends CrudController
{
    public function __construct(ProductRepository $repository)
    {
        $relations = ['category'];
        $orderBy = ['name' => 'asc'];
        parent::__construct($repository, $relations, $orderBy);
    }
}
