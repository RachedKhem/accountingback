<?php

namespace App\Http\Controllers;

use App\Repositories\RoleRepository;

class RoleController extends CrudController
{
	public function __construct(RoleRepository $repository)
	{
		parent::__construct($repository);
	}
}