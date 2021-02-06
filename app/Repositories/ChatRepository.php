<?php

namespace App\Repositories;

use App\Chat;
use App\Repositories\Repository;

class ChatRepository extends Repository
{
    public function __construct()
    {
        $this->model = new Chat();
    }
}
