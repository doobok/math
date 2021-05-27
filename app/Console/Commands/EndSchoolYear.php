<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Student;

class EndSchoolYear extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'students:upclass';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Перевід учнів до наступного класу';

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
        // Отримуємо усіх активних учнів
        $students = Student::where('active', 1)->get();
        // Перебираємо масив
        foreach ($students as $student) {
          // учнів молодше 11 класу переводимо до наступного
          if ($student->class < 11) {
            $student->class = $student->class + 1;
            $student->save();
          } else {
            // 11 класників робимо не активними
            $student->active = 0;
            $student->save();
          }
        }

        // відправляємо сповіщення
        \Illuminate\Support\Facades\Notification::send('', new \App\Notifications\TelegramSimpleMSG('Активні учні переведені до наступного класу. 11 класники помічені, як неактивні'));
    }
}
