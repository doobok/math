<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tutor extends Model
{
    protected $fillable = [
        'name', 'lname', 'mname', 'phone', 'comment', 'active',
    ];
}
