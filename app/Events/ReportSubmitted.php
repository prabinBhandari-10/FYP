<?php

namespace App\Events;

use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ReportSubmitted
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Report $report,
        public User $user
    ) {
    }
}
