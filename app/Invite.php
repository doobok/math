<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invite extends Model
{
    protected $fillable = [
        'name', 'invite', 'role', 'role_id',
    ];
}
