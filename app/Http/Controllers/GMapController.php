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
}
