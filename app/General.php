<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class General extends Model
{
    protected $hidden = ['created_at', 'deleted_at', 'updated_at'];
}

