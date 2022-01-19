<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Goutte\Client;
use App\Http\Lib\Common;
use DateTime;
use DateInterval;
use DatePeriod;
use Response;
use Google\Cloud\Vision\V1\ImageAnnotatorClient;
use Symfony\Component\HttpClient\HttpClient;

class EventSetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // 月末実行
    public function itoyokado_event(){

        $ymd = [];
        $ym = date('Ym', strtotime('next month'));
        $ymd[0] = $ym . '08';
        $ymd[1] = $ym . '18';
        $ymd[2] = $ym . '28';

        $today = date('ymd');

        Log::debug($ymd);
        for($i = 0; $i < count($ymd); $i++){
            $id = "select max(sp_code) + 1 as max_id from sale_point";
            $max = DB::select($id);
            $max_id = $max[0]->max_id;

            $itoyokado = "insert into sale_point
            (sp_code, shop_id, sp_title, sp_subtitle, sp_url, 
            event_start, cash_kubun, register_day)
            values
            ($max_id, 2, '毎月８のつく日はハッピーデー🎉', 
            '店頭にて対象カードご利用で５％オフ', 'https://www.itoyokado.co.jp/special/happyday/index.html', '$ymd[$i]', 
            'セブンカードの現金決済・クレジット決済、nanaco全額決済、クラブオン/ミレニアムカードセゾンのクレジット決済',
            '$today')";
            DB::insert($itoyokado);
            DB::commit();

        }
    }


    // 毎週日曜日実行
    public function summit_event(){
        $tuesday = date('Ymd', strtotime('tuesday next week'));


        $id = "select max(sp_code) + 1 as max_id from sale_point";
        $max = DB::select($id);
        $max_id = $max[0]->max_id;

        $today = date('ymd');

        $summit = "insert into sale_point
        (sp_code, shop_id, sp_title, sp_subtitle, sp_url, 
        event_start, cash_kubun, register_day)
        values
        ($max_id, 8, '毎週火曜日はキャッシュバックサービスデー', '貯まったポイント が 現金で戻る！', 
        'https://www.summitstore.co.jp/otoku/cashback/', '$tuesday', 'サミットポイントカード', '$today')";
        DB::insert($summit);
        DB::commit();
    }
        



    public function event_list(){
        // 必要な素材：　img(src, alt), a(href), 添え付けられたテキスト
        $client = new Client(HttpClient::create(['verify_peer' => false, 'verify_host' => false]));

        

        $obj = new \stdClass();

        $sql_42 = 'select url, element_path from scrape where id = 42';
        $s_42 = DB::select($sql_42);
        foreach($s_42 as $data){
           
            $url = $data->url;
            $crawler = $client->request('GET', $url);
            $link = $crawler->filter($data->element_path)->filter('ul > li > a')
            ->each(function($node){
                return $node->attr('href');
            });

            $src = $crawler->filter($data->element_path)->filter('div > img')
            ->each(function($node){
                return $node->attr('src');
            });

            $alt = $crawler->filter($data->element_path)->filter('div > img')
            ->each(function($node){
                return $node->attr('alt');
            });

            $text = $crawler->filter($data->element_path)->filter('p.campaign_banner_term')
            ->each(function($node){
                return $node->text();
            });

            Log::debug($link);

        }

        $imageAnnotator = new ImageAnnotatorClient();

        $seiyu = [];
        $shop_count = 0;
        for($i = 0; $i < count($src); $i++){
            
            $path = 'https://www.seiyu.co.jp' . $src[$i];  
            

            // Log::debug($eventLink);

            // Log::debug(print_r($obj, true));
            $image = file_get_contents($path);
            $response = $imageAnnotator->documentTextDetection($image);
            $annotation = $response->getFullTextAnnotation();

            # Log::debug out detailed and structured information about document text
            if ($annotation) {
                $allblockText = '';
                // getPages() : OCRによって検出されたページのリスト
                foreach ($annotation->getPages() as $page) {
                    foreach ($page->getBlocks() as $block) {
                        $block_text = '';
                        foreach ($block->getParagraphs() as $paragraph) {
                            foreach ($paragraph->getWords() as $word) {
                                foreach ($word->getSymbols() as $symbol) {
                                    $block_text .= $symbol->getText();
                                }
                                // ↓↓↓　あとで要るかもしれないけど、スペースがあると日付が抽出できないのでコメントアウトにしている
                                // $block_text .= ' ';
                            }
                            $block_text .= " ";
                            // Log::debug($block_text);
                        }
                        $allblockText .= $block_text;
                        
                    }
                }
            }
            $imageAnnotator->close();

            $eventLink = 'https://www.seiyu.co.jp' . $link[$i]; 
            $obj->eventLink = $eventLink;
            $obj->text = $text[$i];
            $obj->alt = $alt[$i];  
            $obj->img_text = $allblockText;

            $seiyu[$shop_count] = $obj;
            $shop_count++;
            Log::debug($seiyu);
            
        }

        $output = [];
        $output['seiyu'] = $seiyu;
        return view('eventList', $output);
            
            
    }


}
