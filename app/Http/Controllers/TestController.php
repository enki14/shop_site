<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Goutte\Client;
use Response;

class TestController extends Controller
{
    

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function scrape() {
        $client = new Client();
        $client_2 = new Client();

        $url = 'https://www.seiyu.co.jp';
        $crawler = $client->request('GET', $url);

        $url_2 = 'https://www.york-inc.com/campaign/';
        $crawler_2 = $client_2->request('GET', $url_2);
        
        // 西友のタイトルとリンク
        $title = $crawler->filter('#area3 > .campaign_banner_sub_list > .campaign_banner_sub_item')
        ->each(function($node){
            return $node->children()->text();      
        });

        $link = $crawler->filter('#area3 > .campaign_banner_sub_list > .campaign_banner_sub_item')
        ->each(function($node){
            return $node->children()->attr('href');
        });


        // ヨークのタイトルと説明
        $title_2 = $crawler_2->filter('#content > .campaign-list > .list > .box > .right-col')
        ->each(function($node){
            return $node->filter('.title')->text();
        });

        $description = $crawler_2->filter('#content > .campaign-list > .list > .box > .right-col')
        ->each(function($node){
            return $node->filter('.description')->text();
        });

        // +1のid付与カラム生成
        $sqld = 'select max(ts.shop_id) + 1 as max_id from testsite ts';
        $max = DB::select($sqld);
        $max_id = $max[0]->max_id;

        //西友のDB保管    
        for($i = 0; $i < count($title); $i++){
            $sql = "select count(*) from testsite where title = '$title[$i]'";
            $cnt = DB::select($sql);

            
            if (count($cnt) == 0) {
                $sqli = "insert into testsite(shop_id, title, link) 
                values($max_id, '$title[$i]', '$link[$i]')";
                DB::insert($sqli);
                DB::commit();
            }
            
        }

        //ヨークのDB保管
        for($i = 0; $i < count($title_2); $i++){
            $sql = "select count(*) as cnt from testsite where title = '$title_2[$i]'";
            $row = DB::select($sql);
            dd($row);

            if($row[0]->cnt == 0){
                $sqli = "insert into testsite(shop_id, title, description) 
                values($max_id, '$title_2[$i]', '$description[$i]')";
                DB::insert($sqli);
                DB::commit();
            }
            
        }  
        
        // select column, →　「 , 」忘れずに
        // caseはlike指定でも可能
        $sql = "select link, title,
        case when substring(link, 1, 4) = 'http' then true 
        else false 
        end as url_flag from testsite";
        $list = DB::select($sql);

        $output = [];
        $output['seiyu_url'] = $url;
        $output['list'] = $list;

        // dd($output);
        return view('scrape', $output);


            //dd($sql);

    }

}