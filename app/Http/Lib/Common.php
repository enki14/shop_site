<?php

namespace App\Http\Lib;

class Common {

    public static function dateFormat($ymd) {
        return date('Y年n月j日', strtotime($ymd));
    }

}