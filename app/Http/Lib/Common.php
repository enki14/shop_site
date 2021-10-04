<?php

namespace App\Http\Lib;

class Common {

    // 8桁数字からの年月日の変換
    public static function dateFormat($ymd) {
        return date('Y年n月j日', strtotime($ymd));
    }

    // 8桁数字からのハイフン付き変換
    public static function  hyphenFormat($data){
        if(!empty($data)){
            return date('Y-m-d', strtotime($data));
        }
      }

}