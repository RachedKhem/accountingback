<?php

namespace App\Repositories;

use App\Provider;

class ProviderRepository extends CrudRepository
{
	public function __construct(Provider $model)
	{
		parent::__construct($model);
	}
}