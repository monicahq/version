<?php

namespace App\Console;

use App\Console\Scheduling\CronEvent;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     *
     * @codeCoverageIgnore
     */
    protected function schedule(Schedule $schedule)
    {
        $this->scheduleCommand($schedule, 'aggregate', 'daily');
        $this->scheduleCommand($schedule, 'cloudflare:reload', 'daily');
    }

    /**
     * Define a new schedule command with a frequency.
     *
     * @codeCoverageIgnore
     */
    private function scheduleCommand(Schedule $schedule, string $command, $frequency)
    {
        $schedule->command($command)->when(function () use ($command, $frequency) {
            $event = CronEvent::command($command);
            if ($frequency) {
                $event = $event->$frequency();
            }

            return $event->isDue();
        });
    }
}
