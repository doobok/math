<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Report;
use Carbon\Carbon;

class ReportQuarterly extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:quarterly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Квартальний звіт';

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
        $reports = Report::where('type', 'monthly')->orderBy('id', 'desc')->limit(3)->get();

        // визначаємо змінні
        $lessons = 0;
        $wage = 0;
        $profit = 0;
        $lessons_count = 0;
        $students_count = 0;
        $pass_count = 0;
        $pass_notpayed_count = 0;
        $pays_in = 0;
        $pays_out = 0;
        $pays_profit = 0;

        // підраховуємо дані
        foreach ($reports as $item) {
          $lessons = $lessons + $item->lessons;
          $wage = $wage + $item->wage;
          $profit = $profit + $item->profit;
          $lessons_count = $lessons_count + $item->lessons_count;
          $students_count = $students_count + $item->students_count;
          $pass_count = $pass_count + $item->pass_count;
          $pass_notpayed_count = $pass_notpayed_count + $item->pass_notpayed_count;
          $pays_in = $pays_in + $item->pays_in;
          $pays_out = $pays_out + $item->pays_out;
          $pays_profit = $pays_profit + $item->pays_profit;
        }

        // записуємо дані в звіт
        if ($reports->count() > 0) {
          $report = new Report;
          $report->lessons = $lessons;
          $report->wage = $wage;
          $report->profit = $profit;
          $report->lessons_count = $lessons_count;
          $report->students_count = $students_count;
          $report->pass_count = $pass_count;
          $report->pass_notpayed_count = $pass_notpayed_count;
          $report->period = Carbon::today()->quarter . ' квартал';
          $report->type = 'quarterly';
          $report->pays_in = $pays_in;
          $report->pays_out = $pays_out;
          $report->pays_profit = $pays_profit;
          $report->errors = 0;
          $report->save();
        }
    }
}
