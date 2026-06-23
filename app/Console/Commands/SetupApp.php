<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class SetupApp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run migrations and seed demo data on first boot (idempotent).';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->call('migrate', ['--force' => true]);

        // Only seed when the database is empty, so restarts never duplicate data.
        if (User::count() === 0) {
            $this->call('db:seed', ['--force' => true]);
        } else {
            $this->info('Database already seeded, skipping.');
        }

        return self::SUCCESS;
    }
}
