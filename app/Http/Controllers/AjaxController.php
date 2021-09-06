<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Response;

class AjaxController extends Controller
{
    public function index(){
        $sql = 'select * from shop';
        $list = DB::select($sql);

        $output = [];
        $output['list'] = $list;
        return view('/ajax', $output);
    }
    
    public function insert(Request $request){
        
        $r_name = $request->input('name');
        
        $sql = 'select max(s.shop_id) + 1 as max_id from shop s';
        $max = DB::select($sql);
        $max_id = $max[0]->max_id;
        
        //全カラムではなく、特定のカラムを指定する場合はテーブル名の後に（）で記す。
        $sql = "insert into shop(shop_id, shop_name) values('$max_id', '$r_name')";
        DB::insert($sql);
        
        DB::commit();
        
        $response = [];
        $response['status'] = "OK";
        return Response::json($response);
        
    }
    
    
    public function update(Request $request){
        $r_id = $request->input('id');
        $r_name = $request->input('name');
        
        $sql = "update shop set shop_name = '$r_name' where shop_id = '$r_id'";
        
        DB::update($sql);
        DB::commit();
        
        $response = [];
        $response['status'] = "OK";
        return Response::json($response);
    }
    
    
    
    public function delete(Request $request){
        $r_id = $request->input('id');
        
        $sql = "delete from shop where shop_id = '$r_id'";
        
        DB::delete($sql);
        DB::commit();
        
        $response = [];
        $response['status'] = "OK";
        
        return Response::json($response);
    }
    
}