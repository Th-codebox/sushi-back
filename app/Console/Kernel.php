<?php

namespace App\Console;

use App\Console\Commands\Payments\CheckPaymentsStatus;
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
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
         $schedule->command('cash_check_box_close')->daily();
         $schedule->command('clear_promo_code')->daily();

         /** Фоновая проверка статусов онлан-платежей кае 15 мин */
         $schedule->command(CheckPaymentsStatus::class)->everyFiveMinutes()->runInBackground();
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
