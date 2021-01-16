<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'App\Console\Commands\LessonsProcessing',
        'App\Console\Commands\ReportWeekly',
        'App\Console\Commands\ReportMonthly',
        'App\Console\Commands\ReportQuarterly',
        'App\Console\Commands\ReportYearly',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('lessons:calculate')->dailyAt('21:30');
        $schedule->command('report:weekly')->weekly()->saturdays()->at('21:45');
        $schedule->command('report:monthly')->monthlyOn(1, '10:00');
        $schedule->command('report:quarterly')->quarterly()->at('11:00');
        $schedule->command('report:yearly')->yearly()->at('12:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
