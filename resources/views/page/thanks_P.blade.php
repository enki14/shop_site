@extends('layouts.layout')
@extends('layouts.header')
@extends('layouts.footer')


@section('title', 'お問い合わせフォーム')


@section('layouts.content')
{{-- topページと違い、content-mainとcontent-containerには"height: auto;をブレード内で採用している --}}
<div id="content-main" class="mx-auto">
    <div id="content_row" class="row">
        <div id="content-container" class="card mt-3 py-5 bg-light mx-auto col-lg-8">
            <div class="card-body">
                @include('layouts.thanks')
            </div>
        </div>
    </div>
    <a href="http://localhost/shop_site/public/" class="history_back"><i class="fas fa-home fa-5x" data-toggle="backtip"></i></a>
</div>  
@endsection
