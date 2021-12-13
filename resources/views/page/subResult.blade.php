@extends('layouts.layout')
@extends('layouts.header')
@extends('layouts.footer')


@section('title', 'キーワード検索の結果')
@section('description', 'スーパーマーケットのポイント情報や、セール情報が検索できるサイトです')
@section('subResultCss')
<link href="css/subResult.css" rel="stylesheet" type="text/css">
@endsection


@section('layouts.content')
<div id="content-main" class="mx-auto">
    <div id="content_row" class="row">
        <div id="content-container" class="card mt-3 py-xl-3 bg-light mx-auto col-lg-8">
            <div class="card-body">
                @include('layouts.keyS')
                @include('layouts.itiran')
            </div>        
        </div>
    </div>
    <div class="d-flex">
        <div class="mx-auto">
            <a href="http://www.facebook.com/share.php?u=https://point-everyday.com/" 
            class="facebook mr-3" rel="nofollow" target="_blank">
                <i class="fab fa-facebook-square fa-5x"></i>
            </a>
            <a href="https://twitter.com/share?url=https://point-everyday.com/
            &text=スーパーマーケットのポイントカード情報を提供します【 東京版 】
            &hashtags=スーパー,ポイントカード,クレジット,お得情報,東京"
            class="twitter mr-3" rel="nofollow" target="_blank">
                <i class="fab fa-twitter-square fa-5x"></i>
            </a>
            <a href="https://social-plugins.line.me/lineit/share?url=https://point-everyday.com/" 
            class="line_button">
                <i class="fab fa-line fa-5x"></i>
            </a>
        </div>
    </div>
    <a href="http://localhost/shop_site/public/" class="history_back"><i class="fas fa-home fa-5x" data-toggle="backtip"></i></a>
</div>
@endsection
