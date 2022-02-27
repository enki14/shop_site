<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\EventSetController;

class Tokyu5off_Command extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tokyu5off';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '東急ストアの5％キャッシュバック';

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
        EventSetController::tokyu_5off();
    }
}
