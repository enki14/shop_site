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
        event_start, cash_kubun, register_day, card_true)
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
            'マルエツカード（クレジット）', '毎月イベント', '$today', 1)";
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
            ($max_id, 20, '毎週金曜・日曜は Tマネー決済の日', 'Ｔマネーで2,000円（税込）以上のお支払いが対象です（お一人様1日1回限り）。', 
            'https://tsite.jp/cp/index.pl?xpg=PCIC0102&cp_id=26108', 
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

    // 当月26日に実施
    // イオングループ全体のもの
    public static function aeon_wakuwaku(){
        $ymd = [];
        $ym = date('Ym', strtotime('next month'));
        $ymd[0] = $ym . '05';
        $ymd[1] = $ym . '15';
        $ymd[2] = $ym . '25';

        $today = date('Ymd');

        for($i = 0; $i < count($ymd); $i++){

            $id = "select max(sp_code) + 1 as max_id from sale_point";
            $max = DB::select($id);
            $max_id = $max[0]->max_id;

            $aeon = "insert into sale_point
            (sp_code, series_id, sp_title, sp_subtitle, sp_url,
            event_start, cash_kubun, keyword, register_day, card_true)
            values
            ($max_id, 1, 'お客様わくわくデー', 
            '毎月5日・15日・25日に対象店舗にて、WAONのお支払いでWAON POINTが2倍となります。', 
            'https://www.waon.net/point/wakuwaku_day/',
            '$ymd[$i]', 'waon', '毎月イベント', '$today', 1)";
            DB::insert($aeon);
            
        }
        DB::commit();

    }

    // 当月５日に実施
    // 2月は30日部分を手書きで変更
    public static function aeon_thanks(){
        $ymd = [];
        $ym = date('Ym', strtotime('this month'));
        $ymd[0] = $ym . '20';
        $ymd[1] = $ym . '30';

        $today = date('Ymd');

        for($i = 0; $i < count($ymd); $i++){
            $id = "select max(sp_code) + 1 as max_id from sale_point";
            $max = DB::select($id);
            $max_id = $max[0]->max_id;

            $aeon = "insert into sale_point
            (sp_code, series_id, sp_title, sp_subtitle, sp_url,
            event_start, cash_kubun, keyword, register_day, card_true)
            values
            ($max_id, 1, 'お客様感謝デー', 
            '各種イオンマークの付いたカードのご利用・読み取り、イオン銀行キャッシュカードの読み取り、
            または電子マネーWAONのお支払いで今ついている本体価格からレジにて５％off', 
            'https://www.waon.net/campaign/otoku/00000000_thanks_day/',
            '$ymd[$i]', 'イオンカード、イオンiDでのクレジット払い、現金など　※WAON POINTカードは割引対象外',
            '毎月イベント', '$today', 1)";
            DB::insert($aeon);
            DB::commit();

        }
    }

    // 当月の第一日曜日実施
    public static function tobu_bonus(){
        $sat = date('Ymd', strtotime('first saturday of next month'));
        $sun = date('Ymd', strtotime('first sunday of this month'));

        $today = date('Ymd');

        $id = "select max(sp_code) + 1 as max_id from sale_point";
        $max = DB::select($id);
        $max_id = $max[0]->max_id;

        $tobu = "insert into sale_point
        (sp_code, shop_id, sp_title, sp_subtitle, sp_url,
        event_start, event_end, cash_kubun, keyword, register_day)
        values
        ($max_id, 20, '商品ボーナスポイントプレゼント', 
        '通常のポイントに加えてさらに商品ボーナスポイントプレゼント', 
        'https://www.tobustore.co.jp/index.php/pickup/card_point',
        '$sun', '$sat', 'Tカード',
        '商品ごとのポイント', '$today')";
        DB::insert($tobu);
        DB::commit();

    }

    public static function tokyu_5off(){
        $ymd = [];
        $ym = date('Ym', strtotime('this month'));
        $ymd[0] = $ym . '19';
        $ymd[1] = $ym . '29';

        $today = date('Ymd');

        for($i = 0; $i < count($ymd); $i++){
            $id = "select max(sp_code) + 1 as max_id from sale_point";
            $max = DB::select($id);
            $max_id = $max[0]->max_id;

            $tokyu = "insert into sale_point
            (sp_code, shop_id, sp_title, sp_subtitle, sp_url,
            event_start, cash_kubun, keyword, register_day)
            values
            ($max_id, 14, 'TOKYU CARD ５％キャッシュバック', 
            'TOKYU CARDでクレジット決済すると請求時に５％分割引されます', 
            'https://www.topcard.co.jp/info/campaign/2202store/index.html',
            '$ymd[$i]', 'TOKYU CARD ClubQ JMBまたは各種ゴールドカードでのクレジット決済',
            '毎月イベント', '$today')";
            DB::insert($tokyu);

        }

        for($i = 0; $i < count($ymd); $i++){
            $id = "select max(sp_code) + 1 as max_id from sale_point";
            $max = DB::select($id);
            $max_id = $max[0]->max_id;

            $presse = "insert into sale_point
            (sp_code, shop_id, sp_title, sp_subtitle, sp_url,
            event_start, cash_kubun, keyword, register_day)
            values
            ($max_id, 133, 'TOKYU CARD ５％キャッシュバック', 
            'TOKYU CARDでクレジット決済すると請求時に５％分割引されます', 
            'https://www.topcard.co.jp/info/campaign/2202store/index.html',
            '$ymd[$i]', 'TOKYU CARD ClubQ JMBまたは各種ゴールドカードでのクレジット決済',
            '毎月イベント', '$today')";
            DB::insert($presse);

        }
        DB::commit();
    
    }

    // 月末表示
    public static function aeon_bonus(){
        $start = date('Ymd', strtotime('first day of next month'));
        $end = date('Ymd', strtotime('last day of next month'));

        $shop = array(4,7);
        $today = date('Ymd');

        for($i = 0; $i < count($shop); $i++){
            $id = "select max(sp_code) + 1 as max_id from sale_point";
            $max = DB::select($id);
            $max_id = $max[0]->max_id;

            $aeon = "insert into sale_point
            (sp_code, shop_id, sp_title, sp_subtitle, sp_url,
            event_start, event_end, cash_kubun, keyword, register_day)
            values
            ($max_id, $shop[$i], '今月の対象商品ボーナスポイント', 
            '※ WAON POINTカードはボーナスポイントの対象外となります', 
            'https://chirashi.otoku.aeonsquare.net/pc/chirashi/bp/',
            '$start', '$end', '電子マネーWAONカード、イオンカード、JMB WAON',
            '商品ごとのポイント', '$today')";
            DB::insert($aeon);
            DB::commit();
        }

    }


    public static function aeon_arigato(){
        $ym = date('Ym', strtotime('this month'));
        $ymd = $ym . '10';

        $today = date('Ymd');

        $id = "select max(sp_code) + 1 as max_id from sale_point";
        $max = DB::select($id);
        $max_id = $max[0]->max_id;

        $aeon = "insert into sale_point
        (sp_code, series_id, sp_title, sp_subtitle, sp_url,
        event_start, cash_kubun, keyword, register_day)
        values
        ($max_id, 1, 'ありが10デー', 
        '基本のポイント５倍！　食料品・衣料品・暮らしの品までほとんど全品対象', 
        'https://www.aeonretail.jp/campaign/ariga10/',
        '$ymd', '電子マネーWAONカード、イオンカード、WAON POINTカード',
        '毎月イベント', '$today')";
        DB::insert($aeon);
        DB::commit();
    }


    // コマンド実施対象外
    // 画像の識字は読み取り専用なのでテーブルに格納できなかった
    public static function seiyu_list(){
        $client = new Client(HttpClient::create(['verify_peer' => false, 'verify_host' => false]));


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

            $text = $crawler->filter($data->element_path)->filter('p.campaign_banner_term')
            ->each(function($node){
                return $node->text();
            });

            $imageAnnotator = new ImageAnnotatorClient();

            $seiyu = [];
            $shop_count = 0;
            for($i = 0; $i < count($src); $i++){
                
                $path = 'https://www.seiyu.co.jp' . $src[$i];  

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
                                    $block_text .= ' ';
                                }
                                $block_text .= " ";
                            }
                            $allblockText .= $block_text;
                        }
                    }
                }
                $imageAnnotator->close();

                $id = "select max(el_id) + 1 as max_id from event_list";
                $max = DB::select($id);
                $max_id = $max[0]->max_id;

                $eventLink = 'https://www.seiyu.co.jp' . $link[$i]; 
                $today = date('Ymd');
    
                Log::debug($text[$i]);

                $sql = "insert into event_list(el_id, el_name, link, el_title, ocr_text, register_day)
                values ($max_id, '西友のイベ一覧', '$eventLink', '$text[$i]', '$allblockText[$i]', $today)";
                DB::insert($sql);
                DB::commit();
                    
            }
        }
               
    }


    // 現在は未使用
    public function itoyokado_list(){
        $sql = "select * from event_list where el_name = 'イトーヨーカドーのイベ一覧'";
        $itoyo = DB::select($sql);
        DB::commit();

        $output = [];
        $output['itoyo'] = $itoyo;
        return view('eventList', $output);
    }



    public static function megadonki_list(){
        $client = new Client(HttpClient::create(['verify_peer' => false, 'verify_host' => false]));


        $sql_51 = 'select url, element_path from scrape where id = 51';
        $s_51 = DB::select($sql_51);
        foreach($s_51 as $data){
           
            $url = $data->url;
            $crawler = $client->request('GET', $url);
            $link = $crawler->filter($data->element_path)->filter('ul > li > a')
            ->each(function($node){
                return $node->attr('href');
            });
            array_splice($link, 0, 2, null);

            $title = $crawler->filter($data->element_path)->filter('ul > li > a > dl > dt')
            ->each(function($node){
                return $node->text();
            });

            $subtitle = $crawler->filter($data->element_path)->filter('ul > li > a > dl > dd')
            ->each(function($node){
                return $node->text();
            });


            for($i = 0; $i < count($title); $i++){
                if(preg_match('/^https/u', $link[$i])){
                    $e_l = $link[$i];
                }else{
                    $e_link = str_replace('.', '', $link[$i]);
                    $e_l = 'https://www.majica-net.com/campaign' . $e_link;
                }

                $today = date('Ymd');

                $id = "select max(el_id) + 1 as max_id from event_list";
                $max = DB::select($id);
                $max_id = $max[0]->max_id; 

                $sql = "insert into event_list(el_id, el_name, link, el_title, el_subtitle, register_day)
                values ($max_id, 'メガドンキのイベ一覧', '$e_l', '$title[$i]', '$subtitle[$i]', $today)";
                DB::insert($sql);
                DB::commit();

                    
            }

        }
    }


    public static function aeon_list(){
        $client = new Client(HttpClient::create(['verify_peer' => false, 'verify_host' => false]));


        $sql_52 = 'select url, element_path from scrape where id = 52';
        $s_52 = DB::select($sql_52);
        foreach($s_52 as $data){
           
            $url = $data->url;
            $crawler = $client->request('GET', $url);
            $link = $crawler->filter($data->element_path)->filter('div.bnr > a')
            ->each(function($node){
                return $node->attr('href');
            });

            $title = $crawler->filter($data->element_path)->filter('div.txt > div.tit > h2')
            ->each(function($node){
                return $node->text();
            });

            $subtitle = $crawler->filter($data->element_path)->filter('div.txt > div.info > p')
            ->each(function($node){
                return $node->text();
            });

            // Log::debug($link);


            for($i = 0; $i < count($title); $i++){
                if(preg_match('/^http/u', $link[$i])){
                    $e_l = $link[$i];
                }else{
                    $e_l = 'https://www.aeonretail.jp' . $link[$i];
                }

                $today = date('Ymd');

                $id = "select max(el_id) + 1 as max_id from event_list";
                $max = DB::select($id);
                $max_id = $max[0]->max_id; 

                $sql = "insert into event_list(el_id, el_name, link, el_title, el_subtitle, register_day)
                values ($max_id, 'イオンのイベ一覧', '$e_l', '$title[$i]', '$subtitle[$i]', $today)";
                DB::insert($sql);
                DB::commit();

                    
            }

        }
    }


    public static function york_list(){
        $client = new Client(HttpClient::create(['verify_peer' => false, 'verify_host' => false]));


        $sql_53 = 'select url, element_path from scrape where id = 53';
        $s_53 = DB::select($sql_53);
        foreach($s_53 as $data){
           
            $url = $data->url;
            $crawler = $client->request('GET', $url);
            $link = $crawler->filter($data->element_path)->filter('a')
            ->each(function($node){
                return $node->attr('href');
            });
            $link_even = array_map('current', array_chunk($link, 2));
            // Log::debug($link_even);

            $title = $crawler->filter($data->element_path)->filter('p.title')
            ->each(function($node){
                return $node->text();
            });
            $title_even = array_map('current', array_chunk($title, 2));
            Log::debug($title_even);
            
            

            $subtitle = $crawler->filter($data->element_path)->filter('p.description')
            ->each(function($node){
                return $node->text();
            });

            // Log::debug($link_even);

            for($i = 0; $i < count($title_even); $i++){

                if(!preg_match('/^http/', $link_even[$i])){
                    $york_link = 'https://www.york-inc.com' . $link_even[$i];
                }else{
                    $york_link = $link_even[$i];
                }

                $today = date('Ymd');

                $id = "select max(el_id) + 1 as max_id from event_list";
                $max = DB::select($id);
                $max_id = $max[0]->max_id; 

                $sql = "insert into event_list(el_id, el_name, link, el_title, el_subtitle, register_day)
                values ($max_id, 'ヨークのイベ一覧', '$york_link', '$title_even[$i]', '$subtitle[$i]', $today)";
                DB::insert($sql);
                DB::commit();

                    
            }

        }
    }



    public static function seizyo_list(){
        $client = new Client(HttpClient::create(['verify_peer' => false, 'verify_host' => false]));


        $sql_54 = 'select url, element_path from scrape where id = 54';
        $s_54 = DB::select($sql_54);
        foreach($s_54 as $data){
           
            $url = $data->url;
            $crawler = $client->request('GET', $url);
            $link = $crawler->filter($data->element_path)
            ->each(function($node){
                return $node->attr('href');
            });
            array_splice($link, 12);

            $title = $crawler->filter($data->element_path)
            ->each(function($node){
                return $node->text();
            });
            array_splice($title, 12);

            for($i = 0; $i < count($title); $i++){

                $today = date('Ymd');

                $id = "select max(el_id) + 1 as max_id from event_list";
                $max = DB::select($id);
                $max_id = $max[0]->max_id; 

                $sql = "insert into event_list(el_id, el_name, link, el_title, register_day)
                values ($max_id, '成城石井のイベ一覧', '$link[$i]', '$title[$i]', $today)";
                DB::insert($sql);
                DB::commit();

                    
            }
        }
    }



    // 不定期イベント出力用（不定期イベント作成後、その都度加えていく）
    public function event_list(){
        $s_1 = "select * from event_list where el_id between 1 and 8";
        $seiyu = DB::select($s_1);

        $s_2 = "select * from event_list where el_name = 'メガドンキのイベ一覧'";
        $donki = DB::select($s_2);

        $s_3 = "select * from event_list where el_name = 'イオンのイベ一覧'";
        $aeon = DB::select($s_3);

        $s_4 = "select * from event_list where el_name = 'ヨークのイベ一覧' order by register_day desc";
        $york = DB::select($s_4);

        $s_5 = "select * from event_list where el_name = '成城石井のイベ一覧' order by register_day desc";
        $seizyo = DB::select($s_5);

        $output = [];
        $output['seiyu'] = $seiyu;
        $output['donki'] = $donki;
        $output['aeon'] = $aeon; 
        $output['york'] = $york;
        $output['seizyo'] = $seizyo;
        return view('eventList', $output);

        DB::commit();

    }

}
