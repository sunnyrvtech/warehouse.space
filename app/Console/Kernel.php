<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Http\Controllers\OrderController;
use App\Job;
use Log;

class Kernel extends ConsoleKernel {

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
    protected function schedule(Schedule $schedule) {
        $schedule->call(function () {
            // Log::info("Cron running " . date('H:i:s'));
              $jobs = Job::get();
              if ($jobs->toArray()) {
              foreach ($jobs as $job) {
                if($job->api == 'order' && $job->method == 'create'){
                    OrderController::createOrder($job);
                }elseif($job->api == 'order' && ($job->method == 'paid' || $job->method == 'cancelled')){
                    OrderController::changeOrderStatus($job);
                }
              }
          }
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands() {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }

}
