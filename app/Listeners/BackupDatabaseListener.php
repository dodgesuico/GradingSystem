<?php
namespace App\Listeners;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class BackupDatabaseListener
{
    public function handle()
    {
        // Trigger a database backup for the specific database connection
        Artisan::call('backup:run', [
            '--only-db' => true,
            '--db-name' => 'gradingsystem', // Ensure this is correctly set in your config
        ]);
    }
}
