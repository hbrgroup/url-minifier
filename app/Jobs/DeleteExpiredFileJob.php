<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class DeleteExpiredFileJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $files = glob(storage_path('app/private/files/*'));

        foreach ($files as $file) {
            if (is_file($file) && filemtime($file) < now()->subDays(21)->timestamp) {
                unlink($file);
            }
        }
    }
}
