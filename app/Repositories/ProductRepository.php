<?php

namespace App\Repositories;

use App\Product;

class ProductRepository extends CrudRepository
{
	public function __construct(Product $model)
	{
		parent::__construct($model);
	}
}