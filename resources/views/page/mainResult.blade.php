@extends('layouts.layout')
@extends('layouts.header')
@extends('layouts.footer')



@section('title', 'メイン検索の結果')
@section('description', 'スーパーマーケットのポイント情報や、セール情報が検索できるサイトです')
@section('resultCss')
<link href="css/result.css" rel="stylesheet" type="text/css">
@endsection

@section('layouts.content')
{{-- topページと違い、content-mainとcontent-containerには"height: auto;をブレード内で採用している --}}
<div id="content-main" class="mx-auto">
    <div id="content-container" class="card mt-3 align-items-center  bg-light mx-auto" 
    style="height: auto; width: 65rem;">
        <div class="card-body">
           @include('layouts.search_value')
           @include('layouts.itiran')
        </div>
    </div>
    <a href="http://localhost/shop_site/public/" class="history_back"><i class="fas fa-home fa-5x" data-toggle="backtip"></i></a>
</div>  
@endsection




