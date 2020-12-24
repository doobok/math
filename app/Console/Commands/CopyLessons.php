<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Lesson;
use App\Report;

class CopyLessons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lessons:copy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Копіювання сьогоднішніх занять на наступний тиждень';

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
        $report = Report::where('type', 'daily')->where('period', Carbon::today()->toDateString())->firstOrFail();

        $computed_err = 0;// помилки обробки

        foreach ($lessons as $lesson) {

          try {
            // створюємо нові уроки на наступний тиждень
            $newLesson = $lesson->replicate();
            $newLesson->pass = null;
            $newLesson->start = Carbon::create($lesson->start)->addWeek();
            $newLesson->end = Carbon::create($lesson->end)->addWeek();
            $newLesson->pass_paid = null;
            $newLesson->computed = 0;
            if ($lesson->period_end === null OR $newLesson->start < $lesson->period_end) {
              $newLesson->save();
            }

            // помічаємо заняття як опрацьоване
            $lesson->computed = 1;
            $lesson->save();

          } catch (\Exception $e) {
            $computed_err++;
          }

          usleep(200000);//чекаємо 0.2 секунди
        }

        $report->errors = $report->errors . '/' . $computed_err;
        $report->save();

        usleep(200000);//чекаємо 0.2 секунди

        echo "2 step ";

        // закінчуємо формування звіту
        $this->call('report:daily');

    }
}
