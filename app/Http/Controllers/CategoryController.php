<?php

namespace App\Http\Controllers;

use App\Repositories\CategoryRepository;

class CategoryController extends CrudController
{
    public function __construct(CategoryRepository $repository)
    {
        $relations = ['products'];
        $orderBy = ['name' => 'asc'];
        parent::__construct($repository, $relations, $orderBy);
    }
}
