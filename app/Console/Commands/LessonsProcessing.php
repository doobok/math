<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Lesson;
use App\Pass;
use App\Pay;
use App\Report;

class LessonsProcessing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lessons:calculate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Обробка занять за день';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $lessons = Lesson::where('computed', false)->whereDate('start', Carbon::today()->toDateString())->get();

        // глобальні змінні
        $lessonsPay = 0;// дохід за заняття
        $wagePay = 0;// комісія тьюторів
        $nextWeek = Carbon::today()->addWeek()->toDateString();// час через тиждень


        foreach ($lessons as $lesson) {

            // // отримуємо студентів з пропусками та формуємо масив з їх ID
            // $passStud = json_decode($lesson->pass);
            // $pStudents = [];
            // if ($passStud != null) {
            //   foreach ($passStud as $ps) {
            //     // створюємо новий пропуск
            //     $pass = new Pass;
            //     $pass->lesson_id = $lesson->id;
            //     $pass->student_id = $ps->id;
            //     $pass->date = $lesson->start;
            //     $pass->save();
            //     array_push($pStudents, $ps->id);
            //   }
            // }
            //
            // // отримуємо студентів записаних на заняття
            // $students = json_decode($lesson->students);
            // foreach ($students as $st) {
            //   if ($lesson->pass_paid) {
            //     $pay = new Pay;
            //     $pay->type = 'lesson-pay';
            //     $pay->student_id = $st->id;
            //     $pay->sum = $lesson->price_student;
            //     $pay->save();
            //
            //     $lessonsPay = $lessonsPay + $lesson->price_student;// сумуємо дохід за заняття
            //
            //   } else if (in_array($st->id, $pStudents)) {
            //     // нічого не робимо для відсутніх
            //   } else {
            //     $pay = new Pay;
            //     $pay->type = 'lesson-pay';
            //     $pay->student_id = $st->id;
            //     $pay->sum = $lesson->price_student;
            //     $pay->save();
            //
            //     $lessonsPay = $lessonsPay + $lesson->price_student;// сумуємо дохід за заняття
            //   }
            // }

            // if ($lesson->price_tutor > 0) {
            //       $pay = new Pay;
            //       $pay->type = 'lesson-wage';
            //       $pay->tutor_id = $lesson->tutor_id;
            //       $pay->sum = $lesson->price_tutor;
            //       $pay->save();
            //
            //       $wagePay = $wagePay + $lesson->price_tutor;// сумуємо комісію тьюторів
            // }

            // // створюємо нові уроки на наступний тиждень
            // $newLesson = $lesson->replicate();
            // $newLesson->pass = null;
            // $newLesson->start = Carbon::create($lesson->start)->addWeek();
            // $newLesson->end = Carbon::create($lesson->end)->addWeek();
            // $newLesson->pass_paid = null;
            // $newLesson->computed = 0;
            // if ($lesson->period_end === null OR $newLesson->start < $lesson->period_end) {
            //   $newLesson->save();
            // }

            // // помічаємо заняття як опрацьоване
            // $lesson->computed = 1;

          usleep(200000);//чекаємо 0.2 секунди
        }

        echo "Dey lessons pay=" . $lessonsPay;
        echo "Dey tutors wage pay=" . $wagePay;

        // записуємо дані в звіт 
        if ($lessons->count() > 0) {
          $report = new Report;
          $report->lessons = $lessonsPay;
          $report->wage = $wagePay;
          $report->profit = $lessonsPay - $wagePay;
          $report->period = Carbon::today()->toDateString();
          $report->save();
        }

    }
}
