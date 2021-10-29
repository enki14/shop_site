<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Goutte\Client;
use Symfony\Component\HttpClient\HttpClient;
use Log;


class ScrapeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:ScrapeCommand';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'scrapecommandのコマンド説明';

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
        $client = new Client(HttpClient::create(['verify_peer' => false, 'verify_host' => false]));

        $url = 'https://www.seiyu.co.jp';
        $crawler = $client->request('GET', $url);
        

        $title = $crawler->filter('#area3 > .campaign_banner_sub_list > .campaign_banner_sub_item')
        ->each(function($node){
            return $node->children()->text();      
        });
        

        $link = $crawler->filter('#area3 > .campaign_banner_sub_list > .campaign_banner_sub_item')
        ->each(function($node){
            return $node->children()->attr('href');
        });
            
        print(count($title));
        for($i = 0; $i < count($title); $i++){
            $sql = "select count(*) as cnt from testsite where title = '$title[$i]'";
            Log::channel('command')->emergency('test!');
            $cnt = DB::select($sql);

            
            if ($cnt[0]->cnt == 0) {
                $sql = 'select max(ts.shop_id) + 1 as max_id from testsite ts';
                $max = DB::select($sql);
                $max_id = $max[0]->max_id;
                
                $sqli = "insert into testsite(shop_id, title, link) 
                values($max_id, '$title[$i]', '$link[$i]')";
                Log::channel('command')->emergency($sqli);
                DB::insert($sqli);
                DB::commit();
            }
            
        }

    }
}
