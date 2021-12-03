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

        // $sql_8 = 'select url, element_path from scrape where id = 8';
        // $s_8 = DB::select($sql_8);
        // foreach($s_8 as $data){
        //     $url = $data->url;
        //     $crawler = $client->request('GET', $url);
        //     $store = $crawler->filter('li .shop_search_individual')
        //     ->filter($data->element_path)
        //     ->each(function($node){
        //         return $node->text();
        //     });
        // }

        // $sql_9 = 'select url, element_path from scrape where id = 9';
        // $s_9 = DB::select($sql_9);
        // foreach($s_9 as $data){
        //     $url = $data->url;
        //     $crawler = $client->request('GET', $url);
        //     $event_day = $crawler->filter('li .shop_search_individual')
        //     ->filter($data->element_path)
        //     ->each(function($node){
        //         return $node->text();
        //     });
        // }
        // Log::debug($store);
        
        
        
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
                $store = $crawler->filter($data->element_path)
                ->filter('div > div > div > h1')
                ->each(function($node){
                    return $node->text();
                });
                $text = $crawler->filter($data->element_path)->filter('div:nth-child(4) > div > div > p.shop_recommend_banner_date')
                ->each(function($node){
                    return $node->text();
                });
            }
            
            // Log::debug($text);
            for($i = 0; $i < count($store); $i++){
                $subtitle = '';
                $subtitle_2 = '';
               

                // Log::debug($text[0][$i]);
                if(preg_match('/^12(\/|-|月)4(土)/', $text[0])){
                    // Log::debug($text);
                        
                    $subtitle .= $store[$i] . '、';
                    $sp_sub = '当日対象店舗：' . $subtitle;
                    // Log::debug($sp_sub);

                    $sql = "insert into sale_point(sp_code, shop_id, sp_title, sp_subtitle) 
                    values(14, 1, 'すべてのセゾンカードで5%off', $sp_sub)";
                    // DB::insert($sql);
                }else{
                    $subtitle_2 .= $store[$i] . '、';
                    $sp_sub_2 = '当日対象店舗：' . $subtitle;
                    Log::debug($sp_sub_2);

                    $sql = "insert into sale_point(sp_code, shop_id, sp_title, sp_subtitle) 
                    values(15, 1, 'すべてのセゾンカードで5%off', $sp_sub_2)";
                    // DB::insert($sql);
                }                
            }
        }

        


    }

}
