<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Refund;

class BunqCheckRefundsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bunq:check-refunds';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check "bunq" refunds states.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        Refund::processQueue();
    }
}
