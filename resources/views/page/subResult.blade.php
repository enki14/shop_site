@extends('layouts.layout')
@extends('layouts.header')
@extends('layouts.footer')


@section('title', 'キーワード検索の結果')
@section('description', 'スーパーマーケットのポイント情報や、セール情報が検索できるサイトです')


@section('layouts.content')
<div id="content-main" class="mx-auto">
    <div id="content-container" class="card mt-3 align-items-center  bg-light mx-auto" 
        style="height: auto; width: 65rem;">
        <div class="card-body">
            @include('layouts.search_value')
            @include('layouts.keyS')
            @include('layouts.MapS')
            @include('layouts.itiran2')
        </div>        
    </div>
</div>
@endsection
