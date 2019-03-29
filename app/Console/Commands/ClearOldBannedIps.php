<?php

namespace App\Console\Commands;

use App\Models\BannedIP;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ClearOldBannedIps extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ip:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear banned IPs older than 30 minutes';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        BannedIP::where('updated_at', '<', Carbon::now()->subMinutes(30)->toDateTimeString())->each(function ($item) {
            $item->delete();
        });
    }
}
