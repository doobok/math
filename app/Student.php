<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'name', 'lname', 'class', 'phone', 'comment', 'active',
    ];
}
