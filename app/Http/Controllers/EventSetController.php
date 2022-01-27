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
    public static function itoyokado_event(){

        $ymd = [];
        $ym = date('Ym', strtotime('next month'));
        $ymd[0] = $ym . '08';
        $ymd[1] = $ym . '18';
        $ymd[2] = $ym . '28';

        $today = date('Ymd');

        Log::debug($ymd);
        for($i = 0; $i < count($ymd); $i++){
            $id = "select max(sp_code) + 1 as max_id from sale_point";
            $max = DB::select($id);
            $max_id = $max[0]->max_id;

            $itoyokado = "insert into sale_point
            (sp_code, shop_id, sp_title, sp_subtitle, sp_url, 
            event_start, cash_kubun, register_day, card_true)
            values
            ($max_id, 2, '毎月８のつく日はハッピーデー🎉', 
            '店頭にて対象カードご利用で５％オフ', 'https://www.itoyokado.co.jp/special/happyday/index.html', '$ymd[$i]', 
            'セブンカードの現金決済・クレジット決済、nanaco全額決済、クラブオン/ミレニアムカードセゾンのクレジット決済',
            '$today', 1)";
            DB::insert($itoyokado);
            DB::commit();

        }
    }


    // 毎週水曜日実行
    public static function summit_event(){
        $tuesday = date('Ymd', strtotime('tuesday next week'));


        $id = "select max(sp_code) + 1 as max_id from sale_point";
        $max = DB::select($id);
        $max_id = $max[0]->max_id;

        $today = date('Ymd');

        $summit = "insert into sale_point
        (sp_code, shop_id, sp_title, sp_subtitle, sp_url, 
        event_start, cash_kubun, register_day, 1)
        values
        ($max_id, 8, '毎週火曜日はキャッシュバックサービスデー', '貯まったポイント が 現金で戻る！', 
        'https://www.summitstore.co.jp/otoku/cashback/', '$tuesday', 'サミットポイントカード', '$today', 1)";
        DB::insert($summit);
        DB::commit();
    }


    // 前週月曜日実施
    public static function maruetsu_5off(){
        $sunday = date('Ymd', strtotime('sunday next week'));

        $today = date('Ymd');
        dd($today);

        $s_id = array(10, 134);
        for($i = 0; $i < count($s_id); $i++){
            $id = "select max(sp_code) + 1 as max_id from sale_point";
            $max = DB::select($id);
            $max_id = $max[0]->max_id;

            $maruetsu = "insert into sale_point
            (sp_code, shop_id, sp_title, sp_subtitle, sp_url, 
            event_start, cash_kubun, keyword, register_day, card_true)
            values
            ($max_id, $s_id[$i], '毎週日曜日は５％OFF !', 
            'マルエツ店舗でのクレジット払いで5%OFF', 'https://www.aeon.co.jp/card/lineup/maruetsu/', '$sunday', 
            'マルエツカード（クレジット）', '毎週イベント', '$today', 1)";
            DB::insert($maruetsu);
            DB::commit();

        }

    }

    // 前月23日に実施
    public static function maruetsu_5times(){
        $firstday = date('Ymd', strtotime('first day of next month'));
        $third_fri = date('Ymd', strtotime('third fri of next month'));

        $today = date('Ymd');
    

        $s_id = array(10, 134);
        for($i = 0; $i < count($s_id); $i++){
            $id = "select max(sp_code) + 1 as max_id from sale_point";
            $max = DB::select($id);
            $max_id = $max[0]->max_id;

            $maruetsu = "insert into sale_point
            (sp_code, shop_id, sp_title, sp_subtitle, sp_url, 
            event_start, cash_kubun, keyword, register_day, card_true)
            values
            ($max_id, $s_id[$i], 'マルエツの対象店舗なら、毎月1日・第3金曜日はWAON POINTが基本の5倍！', 
            'クレジット払いで200円(税込)ごとに5ポイントプレゼント', 'https://www.aeon.co.jp/card/lineup/maruetsu/', '$firstday', 
            'マルエツカード（クレジット）', '毎月イベント', '$today' 1)";
            DB::insert($maruetsu);
            
        }

        for($i = 0; $i < count($s_id); $i++){
            $id = "select max(sp_code) + 1 as max_id from sale_point";
            $max = DB::select($id);
            $max_id = $max[0]->max_id;

            $maruetsu = "insert into sale_point
            (sp_code, shop_id, sp_title, sp_subtitle, sp_url, 
            event_start, cash_kubun, keyword, register_day, card_true)
            values
            ($max_id, $s_id[$i], 'マルエツの対象店舗なら、毎月1日・第3金曜日はWAON POINTが基本の5倍！', 
            'クレジット払いで200円(税込)ごとに5ポイントプレゼント', 'https://www.aeon.co.jp/card/lineup/maruetsu/', '$third_fri', 
            'マルエツカード（クレジット）', '毎月イベント', '$today', 1)";
            DB::insert($maruetsu);
            
        }

        DB::commit();

    }
        

    // 当月初日に実施
    public static function inageya_sannichi(){
        $third_sun = date('Ymd', strtotime('third sun of this month'));
        $today = date('Ymd');

        $s_id = array(11, 146, 147, 154);

        for($i = 0; $i < count($s_id); $i++){
            $id = "select max(sp_code) + 1 as max_id from sale_point";
            $max = DB::select($id);
            $max_id = $max[0]->max_id;

            $inageya = "insert into sale_point
            (sp_code, shop_id, sp_title, sp_subtitle, sp_url, 
            event_start, cash_kubun, keyword, register_day, card_true)
            values
            ($max_id, $s_id[$i], '毎月第３日曜日はさんにち割引', 
            'Vカードご提示で５％OFF', 'https://ingfan.jp/about/', '$third_sun', 
            'ing・fanVカード（クレジット）', '毎月イベント', '$today', 1)";
            DB::insert($inageya);
            
        }

        DB::commit();

    }

    // 前週月曜日に実施
    public static function comodi_donichi(){
        $sat = date('Ymd', strtotime('next saturday'));
        $sun = date('Ymd', strtotime('next sunday'));
        $weekEnd = array($sat, $sun);
        $today = date('Ymd');


        for($i = 0; $i < count($weekEnd); $i++){
            $id = "select max(sp_code) + 1 as max_id from sale_point";
            $max = DB::select($id);
            $max_id = $max[0]->max_id;

            $comodi = "insert into sale_point
            (sp_code, shop_id, sp_title, sp_subtitle, sp_url,
            event_start, cash_kubun, keyword, register_day, card_true)
            values
            ($max_id, 12, '毎週土日はコモカードポイント５倍！', '※一部対象外がございます。', 
            'https://www.comodi-iida.co.jp/event/pdf/cld202201.pdf', '$weekEnd[$i]', 
            'コモカード', '毎週イベント', '$today', 1)";
            DB::insert($comodi);
            DB::commit();
        }

    }

    // 今週土曜日に実施
    public static function keio_3times(){
        $wed = date('Ymd', strtotime('next wednesday'));
        $fri = date('Ymd', strtotime('next friday'));
        $today = date('Ymd');
        $s_id = array(17, 152, 153);

        for($i = 0; $i < count($s_id); $i++){
            $id = "select max(sp_code) + 1 as max_id from sale_point";
            $max = DB::select($id);
            $max_id = $max[0]->max_id;

            $keio = "insert into sale_point
            (sp_code, shop_id, sp_title, sp_url,
            event_start, event_end, cash_kubun, keyword, register_day, card_true)
            values
            ($max_id, $s_id[$i], '毎週水曜日から金曜日はポイント3倍', 'https://www.keiostore.co.jp/service/point.html', 
            '$wed', '$fri', '京王パスポートカード', '毎週イベント', '$today', 1)";
            DB::insert($keio);
            DB::commit();
        }

    }



    // 毎週月曜に実施
    // 3月末までの期間限定
    public static function tobu_Tmoney(){
        $sun = date('Ymd', strtotime('next sunday'));
        $fri = date('Ymd', strtotime('next friday'));
        $week = array($sun, $fri);

        $today = date('Ymd');
        

        for($i = 0; $i < count($week); $i++){
            $id = "select max(sp_code) + 1 as max_id from sale_point";
            $max = DB::select($id);
            $max_id = $max[0]->max_id;

            $tobu = "insert into sale_point
            (sp_code, shop_id, sp_title, sp_subtitle, sp_url,
            event_start, cash_kubun, keyword, register_day, card_true)
            values
            ($max_id, 20, '毎週金曜・日曜は Tマネー決済の日', 'https://tsite.jp/cp/index.pl?xpg=PCIC0102&cp_id=26108', 
            'Ｔマネーで2,000円（税込）以上のお支払いが対象です（お一人様1日1回限り）。', 
            '$week[$i]', 'Tマネー', '期間限定', '$today', 1)";
            DB::insert($tobu);
            DB::commit();
        }
    }

    
    // 毎週月曜日実施
    public static function alps_doniti(){
        $sat = date('Ymd', strtotime('next saturday'));
        $sun = date('Ymd', strtotime('next sunday'));

        $today = date('Ymd');

        $id = "select max(sp_code) + 1 as max_id from sale_point";
        $max = DB::select($id);
        $max_id = $max[0]->max_id;

        $alps = "insert into sale_point
        (sp_code, shop_id, sp_title, sp_url,
        event_start, event_end, cash_kubun, keyword, register_day, card_true)
        values
        ($max_id, 22, '毎週土曜・日曜はポイント２倍デー', 'http://superalps.info/card',
        '$sat', '$sun', '現金・Edy・クレジットカードなど', '毎週イベント', '$today', 0)";
        DB::insert($alps);
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
