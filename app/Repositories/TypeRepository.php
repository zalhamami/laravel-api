<?php

namespace App\Repositories;

use App\Type;
use App\Repositories\Repository;

class TypeRepository extends Repository
{
    public function __construct()
    {
        $this->model = new Type();
    }
}
