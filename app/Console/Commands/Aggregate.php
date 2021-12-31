<?php

namespace App\Console\Commands;

use App\Models\AggregateContactsDay;
use App\Models\AggregateContactsMonth;
use App\Models\AggregateContactsWeek;
use App\Models\Ping;
use App\Services\Aggregate\AggregateDay;
use App\Services\Aggregate\AggregateMonth;
use App\Services\Aggregate\AggregateWeek;
use Illuminate\Console\Command;

class Aggregate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aggregate';

    /**
     * The console command description.
     *
     * @var string|null
     */
    protected $description = 'Aggregate data';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $day = AggregateContactsDay::orderByDesc('date')->first();
        if ($day) {
            $lastDay = $day->date;
        } else {
            $ping = Ping::orderBy('created_at')->first();
            $lastDay = $ping->created_at;
        }

        $week = AggregateContactsWeek::orderByDesc('date')->first();
        if ($week) {
            $lastWeek = $week->date;
        } else {
            $ping = Ping::orderBy('created_at')->first();
            $lastWeek = $ping->created_at;
        }

        $month = AggregateContactsMonth::orderByDesc('date')->first();
        if ($month) {
            $lastMonth = $month->date;
        } else {
            $ping = Ping::orderBy('created_at')->first();
            $lastMonth = $ping->created_at;
        }

        $now = now();

        $this->info('Get days data');
        while ($lastDay < $now) {
            $this->info('  '.$lastDay->format('Y-m-d'));
            app(AggregateDay::class)->execute(['date' => $lastDay]);
            $lastDay = $lastDay->addDay();
        }
        $this->info('Get weeks data');
        while ($lastWeek < $now) {
            $this->info('  '.$lastWeek->format('Y-m-d'));
            app(AggregateWeek::class)->execute(['date' => $lastWeek]);
            $lastWeek = $lastWeek->addWeek();
        }
        $this->info('Get months data');
        while ($lastMonth < $now) {
            $this->info('  '.$lastMonth->format('Y-m-d'));
            app(AggregateMonth::class)->execute(['date' => $lastMonth]);
            $lastMonth = $lastMonth->addMonth();
        }
    }
}
