<?php

namespace App\Console;

use Illuminate\Support\Facades\Log;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Http\Response;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('app:count-modifications-without-quotation')->sundays()->at('4:00')->timezone('Africa/Cairo')->onSuccess(function(){
            Log::info('modification without-quotation counted successfully');
        })->onFailure(function(){
            Log::info('modification without-quotation counted unsuccessfully');
        });
        $schedule->command('app:count-unreported-modifications')->sundays()->at('4:30')->timezone('Africa/Cairo')->onSuccess(function(){
            Log::info('unreported modification counted successfully');
        })->onFailure(function(){
            Log::info('unreported modification counted unsuccessfully');
        });
        
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
