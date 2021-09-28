@extends('layouts.layout')
@extends('layouts.header')
@extends('layouts.footer')



@section('title', 'スーパーマーケットのお得情報')
@section('description', 'スーパーマーケットのポイント情報や、セール情報が検索できるサイトです')

<div class="modal fade" id="list_modal" tabindex="-1" style="display: none"
role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="left: -27rem;">       
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modalHtml">
                <div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
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
    <a href="http://localhost/shop_site/public/" class="history_back"><i class="fas fa-chevron-circle-up fa-5x" data-toggle="scroll_top"></i></a>
</div>
<script src="https://maps.googleapis.com/maps/api/js?language=ja&region=JP&key=AIzaSyBAEY8ljaq0u8SXzKNz_M2GGKGahHJYpAo&callback=initMap&libraries=places" async defer></script>
@endsection
