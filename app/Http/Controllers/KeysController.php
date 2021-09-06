<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KeysController extends Controller
{
    public function index(){

        $output = [];
        return view('page.top', $output);
    }


    public function keyRes(Request $request){
        $key_1 = $request->input('key_1');
        $key_2 = $request->input('key_2');
        $key_3 = $request->input('key_3');
        $key_4 = $request->input('key_4');
        $key_5 = $request->input('key_5');
        $key_6 = $request->input('key_6');
        $key_7 = $request->input('key_7');
        $key_8 = $request->input('key_8');
        $key_9 = $request->input('key_9');



    }
}
