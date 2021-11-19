<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Goutte\Client;
use App\Http\Lib\Common;
use Response;
use Symfony\Component\HttpClient\HttpClient;

class EventSetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function seiyu_5pctOff(){
        $client = new Client(HttpClient::create(['verify_peer' => false, 'verify_host' => false]));

        $sql = "select s.shop_name, s2.store_name from shop s inner join store s2 on s.shop_id = s2.shop_id where s.shop_id = 1";
        $getUrl = DB::select($sql);
        // dd($getUrl);
        foreach($getUrl as $dataUrl){
            $sql_9 = 'select url, element_path from scrape where id = 9';
            $s_9 = DB::select($sql_9);
            foreach($s_9 as $data){
                $url = $data->url . $dataUrl->shop_name . $dataUrl->store_name;
                // dd($url);
                $crawler = $client->request('GET', $url);
                $text = $crawler->filter($data->element_path)
                ->each(function($node){
                    return $node->text();
                });
            }
            Log::debug($text);

            // if($text[2] == '11/20(土)、11/27(土)'){

            // }else{

            // }
            
        }
    }

}
