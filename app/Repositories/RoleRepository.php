<?php

namespace App\Repositories;

use App\Role;

class RoleRepository extends CrudRepository
{
	public function __construct(Role $model)
	{
		parent::__construct($model);
	}
}