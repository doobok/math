<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TelegramState extends Model
{
    protected $fillable = [
        'userId', 'command', 'action', 'params',
    ];

    public $timestamps = false;
}
