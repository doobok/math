<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $fillable = [
        'name', 'start', 'end', 'color', 'comment', 'price_student', 'price_tutor', 'tutor_id', 'classroom_id', 'students', 'period_end', 'pass', 'pass_paid', 'timed'
    ];
}
