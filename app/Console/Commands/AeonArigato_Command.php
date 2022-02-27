<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\EventSetController;

class AeonArigato_Command extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aeonArigato';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'イオンのありが１０デー';

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
     * @return int
     */
    public function handle()
    {
        EventSetController::aeon_arigato();
    }
}
