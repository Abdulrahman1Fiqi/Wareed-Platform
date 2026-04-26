<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BloodRequest;


class ExpireBloodRequests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wareed:expire-requests';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mark blood requests as expired when their time window has passed';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = BloodRequest::where('status', 'active')
            ->where('expires_at', '<=', now())
            ->update(['status' => 'expired']);

        $this->info("Marked {$count} request(s) as expired.");
        
    }
}
