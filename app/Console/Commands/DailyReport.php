<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Pay;
use App\Report;

class DailyReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Формування фінансового звіту за день';

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
        $pays = Pay::where('type', 'NOT LIKE', 'lesson%')->whereDate('created_at', Carbon::today()->toDateString())->get();
        $report = Report::where('type', 'daily')->where('period', Carbon::today()->toDateString())->firstOrFail();

        $incoming = 0;
        $outgoing = 0;
        $computed_err = 0;// помилки обробки

        foreach ($pays as $pay) {
          try {

            if ($pay->type === 'refill') {
              $incoming = $incoming + $pay->sum;
            } else {
              $outgoing = $outgoing + $pay->sum;
            }

          } catch (\Exception $e) {
            $computed_err++;
          }

        }

        $report->pays_in = $incoming;
        $report->pays_out = $outgoing;
        $report->pays_profit = $incoming - $outgoing;
        $report->errors = $report->errors . '/' . $computed_err;
        $report->save();

        echo "3 step ";

    }
}
