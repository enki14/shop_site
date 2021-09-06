<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Vender\CallTwitterApi;
use Illuminate\Support\Facades\Log;

class TwitterController extends Controller
{
        
    private function dateFormat(string $mm, string $dd){
        // str_padで2桁の左寄せで0埋め
        $mm = str_pad($mm, 2, "0", STR_PAD_LEFT);
        $dd = str_pad($dd, 2, "0", STR_PAD_LEFT);
        if($mm == '01'){
            // dateの第１引数はstringなのでYにクォーテーションをつける
            $yyyy = date('Y', strtotime('+1 year'));
        }else{
            $yyyy = date('Y');
        }
        return $yyyy . $mm . $dd;

    }

    private function regExpressionTweet($replace_text){

        $retObj = new \stdClass();
        $retObj->START_DATE = "";
        $retObj->END_DATE = "";

        $date_wa_pattern = '/(0[1-9]{1}|1[0-2]{1}|[1-9]{1})[\/\-\.\／－．月](3[0-1]{1}|[1-2]{1}[0-9]{1}|0[1-9]{1}|[1-9]{1})[\/\-\.\／－．日]/u';
        $date_slash_pattern = '/(0[1-9]{1}|1[0-2]{1}|[1-9]{1})\/(3[0-1]{1}|[1-2]{1}[0-9]{1}|0[1-9]{1}|[1-9]{1})/u';

        // ○○月○○日分のSTART_DATEとEND_DATEの生成
        preg_match($date_wa_pattern, $replace_text, $match);

        if(count($match) > 0){
            // $match[1]が○○月の数字のみ、$match[2]が○○日の数字のみ　　// 上のdateFormat関数で2桁分の0埋めを実施している　　// dateFormatで年も取ってくれる
            $retObj->START_DATE = $this->dateFormat($match[1], $match[2]);
            

            $replace_text = mb_ereg_replace($match[0], '', $replace_text);

            preg_match($date_wa_pattern, $replace_text, $match);

            if(count($match) > 0){
                $retObj->END_DATE = $this->dateFormat($match[1], $match[2]);
                $replace_text = mb_ereg_replace($match[0], '', $replace_text);
            }
        }

        // ○○/○○分のSTART_DATEとEND_DATEの生成
        preg_match($date_slash_pattern, $replace_text, $match);

        if(count($match) > 0 && $retObj->START_DATE == ""){
            $retObj->START_DATE = $this->dateFormat($match[1], $match[2]);
            $replace_text = mb_ereg_replace($match[0], '', $replace_text);

            preg_match($date_slash_pattern, $replace_text, $match);

            if(count($match) > 0){
                $retObj->END_DATE = $this->dateFormat($match[1], $match[2]);
                $replace_text = mb_ereg_replace($match[0], '', $replace_text);
            }
        }

        return $retObj;
    }



    public function index(Request $request)
    {
        $sql = $sql = "select * from shop where twitter_user_id != ''
        and shop_id not in (4,7,8,14,28,98,127)";
        $list = DB::select($sql);

        $t = new CallTwitterApi();

        $ad = [];
        $shop_count = 0;
        foreach($list as $data){
            $apilist = $t->userTimeline($data->twitter_user_id);
            

            $obj = new \stdClass();
            $obj->shop_name = $data->shop_name;
            $apicount = 0;
            $adsub = [];
            
            foreach($apilist as $apidata){
                $objsub = new \stdClass();

                $pattern = array(
                    '/#/',
                    '/＼/',
                    '/／/',
                    '/.*@[0-9a-zA-Z_]+\s/',
                    '/^RT\s@[0-9a-zA-Z_]{1,15}:\s/',
                    '/[\xF0-\xF7][\x80-\xBF][\x80-\xBF][\x80-\xBF]/',
                    '/(?:\xEE[\x80\x81\x84\x85\x88\x89\x8C\x8D\x90-\x9D\xAA-\xAE\xB1-\xB3\xB5\xB6\xBD-\xBF]|\xEF[\x81-\x83])[\x80-\xBF]/'
                );
                $replace_text = preg_replace($pattern, '', strstr($apidata->text, 'http', true));
                
                if($replace_text != ''){
                    $objsub->text = $replace_text;
                    $adsub[$apicount] = $objsub;
                    $apicount++;

                    $shop_id = $data->shop_id;  //shopテーブルからの取得
            
                    $sql = "select max(sp.sp_code) + 1 as sp_id from sale_point sp";
                    $max = DB::select($sql);
                    $sp_id = $max[0]->sp_id;

                    // $replace_textの同じデータが重複しないように$cntを設けている
                    $sql = "select count(*) as cnt from sale_point 
                    where sp_subtitle = '$replace_text' and shop_id = $shop_id";
                    $cnt = DB::select($sql);
                    
                    if($cnt[0]->cnt == 0){
                        
                        $ret = $this->regExpressionTweet($replace_text);
                        // 日付なしの場合でも空の値がセットされるだけなので条件分岐は行わなくてよし
                        // START_DATEとEND_DATEの空の値はregExpressionTweet()で定義されている
                        $insert = "insert into sale_point(shop_id, sp_code, sp_subtitle, event_start, event_end) 
                        values($shop_id, $sp_id, '$replace_text', '$ret->START_DATE', '$ret->END_DATE')";

                        DB::insert($insert);
                        DB::commit();
                    }                    
                    
                }
                
            }   
            $obj->messages = $adsub;
            $ad[$shop_count] = $obj;
            $shop_count++;
        }
    
        $output = [];
        $output['ad'] = $ad;
        return view('twitter', $output);
    }

    


 
    public function uSearch(Request $request)
    {
        $t_u = new CallTwitterApi();
        $daiei = $t_u->searchUser("daieiOFFICIAL");
        $donki = $t_u->searchUser("donki_donki");
        $tokyu = $t_u->searchUser("TokyuStore");
        $summit = $t_u->searchUser("summitstore_co");
        $olympic = $t_u->searchUser("olympic_osc");
        $ozamu = $t_u->searchUser("ozam_official");
        $superalps = $t_u->searchUser("superalps_co");
        $ysmart77 = $t_u->searchUser("ysmart77");
        $belc = $t_u->searchUser("belc_jp");
        $shinanoya = $t_u->searchUser("shinanoyaWeb");
        $foodway = $t_u->searchUser("foodway_group");
        $h_nakamuraya = $t_u->searchUser("hirainakamuraya");
        $kitamura = $t_u->searchUser("Super_Kitamura");
        $sakae = $t_u->searchUser("ShoppingSAKAE");
        $FoodStoreAoki = $t_u->searchUser("FoodStoreAoki");
        $hatijostore = $t_u->searchUser("8jostore");
        $yosiike = $t_u->searchUser("yoshiike_group");
        $amica = $t_u->searchUser("oomitsu_amica");
        $seisen = $t_u->searchUser("taiyo_toyocyou");
        $biocebon = $t_u->searchUser("Bio_c_Bon_Japon");
        $kitanoAce = $t_u->searchUser("kitano_ace");
        $n_azabu = $t_u->searchUser("NATIONAL_AZABU");
        $NissinNWD = $t_u->searchUser("NissinNWD");

        
        $output = [
            'daiei' => $daiei,
            'donki' => $donki,
            'tokyu' => $tokyu,
            'summit' => $summit,
            'olympic' => $olympic,
            'ozamu' => $ozamu,
            'superalps' => $superalps,
            'ysmart77' => $ysmart77,
            'belc' => $belc,
            'shinanoya' => $shinanoya,
            'foodway' => $foodway,
            'h_nakamuraya' => $h_nakamuraya,
            'kitamura' => $kitamura,
            'sakae' => $sakae,
            'FoodStoreAoki' => $FoodStoreAoki,
            'hatijostore' => $hatijostore,
            'yosiike' => $yosiike,
            'amica' => $amica,
            'seisen' => $seisen,
            'biocebon' => $biocebon,
            'kitanoAce' => $kitanoAce,
            'n_azabu' => $n_azabu,
            'NissinNWD' => $NissinNWD
        ];
        // dd($output);
        return view('twitter', ['output' => $output]);


    //     $d = $t_u->searchTweets("スーパーマーケット");


    //     $user = array("AEON_JAPAN", "Seiyu_Japan", "Lets_go_Yokado");
        
    //     //代入するための変数をセット
    //     $u_s = [];
    //     for($i = 0; $i < count($user); $i++){

    //         //代入する $u_s側にも[$i]を指定しなければ、配列が上書きされるだけの仕様になってしまう
    //         $u_s[$i] = $t_u->userShow($user[$i]);
    //     }
        
    //     $output = [];
    //     $output['account'] = $d_u;
    //     $output['keyword'] = $d;
    //     $output['user'] = $u_s;
        
    //     //dd($d);
    //     //dd($u_s);
    //     return view('twitter', $output);

    
    
    // dd($daiei);
        // for($i = 0; $i < count($aeon); $i++){
        //     //dd($at);

        //     $at = $aeon[$i]->text;

        //     $sqlc = 'select max(sp.sp_code) + 1 as max_code from sale_point sp';
        //     $max_c = DB::select($sqlc);
        //     $max_code = $max_c[0]->max_code;

        //     $sqlid = 'select max(sp.shop_id) + 1 as max_id from sale_point sp';
        //     $max_i = DB::select($sqlid);
        //     $max_id = $max_i[0]->max_id;

        //     $sql_is = "insert into sale_point(sp_code, shop_id, sp_title) values($max_code, $max_id, '$at[$i]')";
        //     DB::insert($sql_is);
        //     DB::commit();

        //     //dd($aeon);
    }
}
