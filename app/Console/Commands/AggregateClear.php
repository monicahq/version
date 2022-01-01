<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Support\Facades\DB;

class AggregateClear extends Command
{
    use ConfirmableTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aggregate:clear
                            {--force : Force the operation to run when in production.}';

    /**
     * The console command description.
     *
     * @var string|null
     */
    protected $description = 'Clear all aggregate data';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        if ($this->confirmToProceed()) {
            DB::table('aggregate_contacts_days')->truncate();
            DB::table('aggregate_contacts_weeks')->truncate();
            DB::table('aggregate_contacts_months')->truncate();
        }
    }
}
