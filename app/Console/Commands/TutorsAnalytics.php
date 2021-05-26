<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Tutor;
use Carbon\Carbon;
use App\Lesson;
use App\Performance;

class TutorsAnalytics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tutors:stats';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Отримуємо аналітику ефективності тьютора';

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
        $tutors = Tutor::where('active', 1)->get();

        foreach ($tutors as $tutor) {

          $c_profit = 0;
          $t_profit = 0;
          // отримуємо усі заняття за місяць який закінчився
          $lessons = Lesson::where('computed', true)->where('tutor_id', $tutor->id)->whereDate('created_at', '>=', Carbon::yesterday()->startOfMonth()->toDateString())->get();

          foreach ($lessons as $lesson) {
            $students = count(json_decode($lesson->students));
            $pass = 0;
            if ($lesson->pass) {
              $pass = count(json_decode($lesson->pass));
            }
            // обраховуємо дохід компанії
            $c_profit = $c_profit + ($lesson->price_student * ($students - $pass)) - $lesson->price_tutor;
            // обраховуємо дохід тьютора
            $t_profit = $t_profit + $lesson->price_tutor;

          }

          // рахуємо KPI
          $kpi = 0;

          if ($lessons->count() > 0) {
            $kpi = $c_profit / ($t_profit + $c_profit) * 100;
          }

          $report = new Performance;
          $report->lessons = $lessons->count();
          $report->company_profit = $c_profit;
          $report->tutor_profit = $t_profit;
          $report->tutor_id = $tutor->id;
          $report->kpi = round($kpi);
          $report->save();
        }

        // відправляємо сповіщення
        \Illuminate\Support\Facades\Notification::send('', new \App\Notifications\TelegramSimpleMSG('KPI для тьюторів сформовані'));

    }
}
