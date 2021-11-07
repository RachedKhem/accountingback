<?php

namespace App\Repositories;

use App\Category;

class CategoryRepository extends CrudRepository
{
	public function __construct(Category $model)
	{
		parent::__construct($model);
	}
}