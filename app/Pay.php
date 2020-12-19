<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pay extends Model
{
    // операції з балансом
    protected static function boot()
    {
        static::saving(function($instance)
        {          
          // списуємо вартість заняття із студента
          if ($instance->type === 'lesson-pay') {
            $student = Student::findOrFail($instance->student_id);
            $student->balance = $student->balance - $instance->sum;
            $student->save();
          }

          // поповнюємо баланс тьютора
          if ($instance->type === 'lesson-wage') {
            $tutor = Tutor::findOrFail($instance->tutor_id);
            $tutor->balance = $tutor->balance + $instance->sum;
            $tutor->save();
          }


        });

        parent::boot();
    }
}
