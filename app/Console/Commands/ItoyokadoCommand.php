<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use app\Http\Controllers\EventSetController;

class ItoyokadoCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:itoyokado_event';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '毎月のイトーヨーカドーイベント';
    
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
        EventSetController::itoyokado_event();
    }
}
