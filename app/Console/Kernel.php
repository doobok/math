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
        'App\Console\Commands\EndSchoolYear',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('lessons:calculate')->dailyAt('23:59'); // щоденний звіт
        $schedule->command('report:weekly')->weekly()->sundays()->at('09:00'); // тижневий звіт
        $schedule->command('report:monthly')->monthlyOn(1, '10:00'); // звіт за місяць
        $schedule->command('report:quarterly')->quarterly()->at('11:00'); // квартальний звіт
        $schedule->command('report:yearly')->yearly()->at('12:00'); // річний звіт
        $schedule->command('students:upclass')->cron('0 2 5 6 *'); // перевід учнів до наступного класу
        $schedule->command('students:stats')->weekly()->sundays()->at('02:20'); // KPI учнів
        $schedule->command('tutors:stats')->monthlyOn(1, '02:30'); // KPI тьюторів
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
