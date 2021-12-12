<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Response;

class GMapController extends Controller
{

    public function index(Request $request){
        $output = [];
        return view('google', $output); 
    }



    public function shopInfo(){

        $sql = "select s2.shop_id, s3.store_id, s2.shop_name, s3.store_name, 
        sp.event_start, sp.event_end, sp.sp_title, X(location) as lat, Y(location) as lng  
        from shop s2 left join store s3 on s2.shop_id = s3.shop_id 
        left join sale_point sp on s2.shop_id = sp.shop_id
        where X(location) is not null and Y(location) is not null";
        $list = DB::select($sql);
        
        Log::debug($list);
        $response = [];
        $response['list'] = $list;
        return response()->json($response);

       
    }

     // モーダル表示用メソッド
     public function gooResult(Request $request){
        // MapS.bladeのmap_search, name属性
        $req = $request->input('namae');


        // スペースあるなしで対応する曖昧検索
        $words = str_replace("　", " ", $req);
        $words = trim($words);
        $word_array = preg_split("/[ ]+/", $words);

        $add_where = "";
        for($i =0; $i < count($word_array); $i++){
            if(count($word_array) > 1){
                if($i == 0){
                    $add_where .= "(s.shop_name LIKE '%$word_array[$i]%' or s2.store_name like '%$word_array[$i]%' 
                    or s2.town like '%$word_array[$i]%' or s2.ss_town like '%$word_array[$i]%') ";
                }else{
                    $add_where .= "and (s.shop_name LIKE '%$word_array[$i]%' or s2.store_name like '%$word_array[$i]%' 
                    or s2.town like '%$word_array[$i]%' or s2.ss_town like '%$word_array[$i]%') ";
                }
            }else{
                $add_where .= "s.shop_name LIKE '%$word_array[$i]%' or s2.store_name like '%$word_array[$i]%' 
                or s2.town like '%$word_array[$i]%' or s2.ss_town like '%$word_array[$i]%' ";
            }
            
        }

        $add_order = "limit 10";

        // GLengthで２地点距離の計算式をカラムにした。検索結果から近い順に店舗をselectしている。
        $sql = "select s.shop_name, s2.store_name, s2.store_address, 
        X(s2.location) as lat, Y(s2.location) as lng 
            from shop s inner join store s2 on s.shop_id = s2.shop_id  
            where " . $add_where . $add_order;
        $list = DB::select($sql);
        
        $response = [];
        $response['list'] = $list; 
        return Response::json($response);
    
    }
}
