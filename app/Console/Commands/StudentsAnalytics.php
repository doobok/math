<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Student;
use Carbon\Carbon;
use App\Pay;
use App\Performance;

class StudentsAnalytics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'students:stats';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Отримуємо аналітику ефективності учня за тиждень';

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
     * @return int
     */
    public function handle()
    {
        $students = Student::where('active', 1)->get();

        foreach ($students as $student) {

          $profit = 0;
          // отримуємо усі заняття за 7 днів
          $pays = Pay::where('student_id', $student->id)->where('type', 'lesson-pay')->whereDate('created_at', '>', Carbon::today()->subDays(7)->toDateString())->get();

          foreach ($pays as $pay) {
            $profit = $profit + $pay->sum;
          }

          // рахуємо KPI
          $kpi = 0;

          if ($pays->count() > 0) {
            $kpi = ($profit / $pays->count()) / 7;
          }

          $report = new Performance;
          $report->lessons = $pays->count();
          $report->company_profit = $profit;
          $report->student_id = $student->id;
          $report->kpi = round($kpi, 3);
          $report->save();

        }

        // відправляємо сповіщення
        \Illuminate\Support\Facades\Notification::send('', new \App\Notifications\TelegramSimpleMSG('KPI для учнів сформовані'));

    }
}
