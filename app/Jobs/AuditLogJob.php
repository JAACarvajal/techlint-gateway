<?php

namespace App\Jobs;

use App\AuditLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class AuditLogJob implements ShouldQueue
{
    use Queueable;

    /**
     * Data to be logged
     * @var
     */
    protected $data;

    /**
     * Create a new job instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        AuditLog::create($this->data);
    }
}
