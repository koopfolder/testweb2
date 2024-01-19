<?php

namespace App\Console;
use App\Modules\Api\Http\Controllers\MediaController;
use App\Modules\Api\Http\Controllers\IndexController;
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


        // $schedule->call(function () {
        //     // php artisan schedule:work
        //     $obj = new MediaController();
        //     $obj->UpdatePersonaFileMedia();
        // })
        // ->everyMinute();
        

        $schedule->call(function () {
            // php artisan schedule:work
            $obj = new MediaController();
            $obj->getExcelReport();
        })
        ->hourly();

        $schedule->call(function () {
            // php artisan schedule:work
            $obj = new MediaController();
            $obj->getExcelReport_test();
        })
        ->hourly();

        $schedule->call(function () {
            // php artisan schedule:work
            $obj = new MediaController();
            $obj->MediaJobFileCheck();
        })
        ->daily();
        
        $schedule->call(function () {
            // php artisan schedule:work
            $obj = new MediaController();
            $obj->job_user_connect();
        })
        ->daily();


        
        // $schedule->call(function () {
        //     // php artisan schedule:work
        //     $obj = new IndexController();
        //     $obj->UpdateMediaTermStatusFromExcel();
        // })
        // ->everyMinute();
     
        // $schedule->call(function () {
        //     // php artisan schedule:work
        //     $obj = new MediaController();
        //     $obj->GetPersonaMedia();
        // })
        // ->everyMinute();
        
        // $schedule->call(function () {
        //     // php artisan schedule:work
        //     $obj = new IndexController();
        //     $obj->GetMeidaFileFromDol();
        // })
        // // ->everyMinute();
        // ->cron('30 14 * * *');

        $schedule->call(function () {
            // php artisan schedule:work
            $obj = new IndexController();
            $obj->GetMeidaFileFromDol();
        })
        ->cron('30 14 * * *');

        $schedule->call(function () {
            // php artisan schedule:work
            $obj = new IndexController();
            $obj->GetMeidaFileFromDol();
        })
        ->cron('15 18 * * *');
       

        $schedule->call(function () {
            // php artisan schedule:work
            $obj = new IndexController();
            $obj->GetMeidaFileFromDol();
        })
        ->cron('30 05 * * *');
        
        // $schedule->command('inspire')
        //          ->hourly();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
