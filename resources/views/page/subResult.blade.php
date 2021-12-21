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
        <div id="content-container" class="card mt-3 py-3 bg-light mx-auto col-lg-8">
            <div class="card-body">
                @include('layouts.keyS')
                @include('layouts.itiran')
            </div>        
        </div>
    </div>
    <a href="http://localhost/shop_site/public/" class="history_back"><i class="fas fa-home fa-5x" data-toggle="backtip"></i></a>
</div>
@endsection
