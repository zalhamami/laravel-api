<?php

namespace App;

class Chat extends General
{
    protected $fillable = ['message', 'user_id'];

    protected $with = ['user'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
