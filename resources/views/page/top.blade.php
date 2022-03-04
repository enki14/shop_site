@extends('layouts.layout')
@extends('layouts.header')
@extends('layouts.footer')



@section('title', 'スーパーマーケットのポイントカード情報♪　～東京都～')
@section('description', 'スーパーマーケットのポイントカードのお得な情報が検索できるサイトです♪♪')
{{-- カレンダー用モーダル --}}
<div id="calendarModal" class="modal fade" aria-hidden="true" style="display: none">
    <div class="modal-dialog modal-dialog-centered">
        <div id="modal_frame" class="modal-content">
            <h5 id="modalShop" class="text-center pb-5 font-weight-bold text-dark"></h5>
            <div class="modal_conta container bg-light py-4">
                <span id="modal-date" class="pl-4 mt-3 font-weight-bold"></span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>    
                    <span class="sr-only">close</span>
                </button>
                <div class="modal-body mb-4">
                    <h4 id="modalTitle" class="modal-title pb-2 px-sm-3"></h4>
                    <div id="modal_description" class="px-3 mb-3 mt-2"></div>
                    <div class="container mt-5">
                        <div class="row c_name">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- マップ検索用モーダル --}}
<div class="modal fade" id="list_modal" tabindex="-1" style="display: none"
role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">       
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modalHtml"></div>
        </div>
    </div>
</div>
@section('layouts.content')
<div id="content-main">
    <div id="content_row" class="row">
        <div id="content-container" class="card mt-3 col-lg-8 mx-auto">
            <div class="container">
                <div id="lens-set" class="col-lg-12 d-flex justify-content-center" style="top: -120%;">
                    <i id="lens" class="fas fa-circle fa-sm"></i>
                </div>
            </div>
            <div id="smart-dis" class="card-body bg-light p-5 my-5 mx-2">
                @include('layouts.search_value')
                @include('layouts.keyS')
                @include('layouts.calendar_2')
                @include('layouts.MapS')
                @include('layouts.itiran2')
            </div>
            <div class="container">
                <div class="col-lg-12 d-flex justify-content-center" style="top: 30%;">
                    <button type="button" class="btn bg-light smart-button"></button>
                </div>
            </div>
        </div>
        {{--@include('layouts.sidebar')--}}
    </div>
</div>
@endsection
@section('resultScript')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'listWeek',
            headerToolbar: {
                    left: 'prev,next',
                    center: 'title',
                    right: ''
            },
            // あとはShopsiteController@eventCalendar_2メソッドでDBをレスポンスすればうまくやってくれる
            // indexメソッドの方には特に初期値など要らない
            events: "eventCalendar_2",
            // eventClickやeventDidMountなどの関数は、それぞれのfc-event-titleにすでに機能している
            eventClick: function(info){
                info.jsEvent.preventDefault();
                let c_name = info.event._def.extendedProps.c_name;
                let c_link = info.event._def.extendedProps.c_link;
                let start = new Date(info.event.start);
                let end = new Date(info.event.end);
                console.log(start);
                let s_month = start.getMonth() + 1;
                let s_day = start.getDate();
                let e_month = end.getMonth() + 1;
                let e_day = end.getDate();
                let def = 'Thu Jan 01 1970 09:00:00 GMT+0900 (GMT+09:00)';
                // モーダルの日付表示
                if(end != def){
                    $('#modal-date').html('開催日　' + s_month + '月' + s_day + '日 ~ ' + e_month + '月' + e_day + '日');
                }else{
                    $('#modal-date').html('開催日　' + s_month + '月' + s_day + '日');
                }
                
                // モーダルのタイトルを追加
                $('#modalShop').html(info.event._def.title);
                // タイトルのセット(append()ではなくhtml()を使う)
                $('#modalTitle').html($('<a></a>', {href: info.event._def.url, target: "_blank", class: "modal_a"} )
                .text(info.event._def.extendedProps.main_title)); 
                // モーダルの本文をセット
                $('#modal_description').html(info.event._def.extendedProps.description);
                $('.c_name').html(cardOutput_2(c_name, c_link));
                // モーダル着火
                $('#calendarModal').modal(); 
                
            },
            dayMaxEventRows: true, 
            views: {
                dayGrid: {
                    dayMaxEventRows: 6 
                }
            },
            locale: 'ja',
            buttonText: {
                prev:     '<',
                next:     '>',
                prevYear: '<<',
                nextYear: '>>',
                today:    '今月',
                month:    '月',
                list:     '週間'
            },
            
        });
        
        calendar.render()
    });

    
    function cardOutput_2(c_name, c_link){
        if(c_name === null || c_name == ''){
            return '';
        }else if(c_name.match(/,/)){
            c_name = c_name.split(',');
            c_link = c_link.split(',');
            console.log(c_name);
            for(let i = 0; i < c_name.length; i++){
                return "<span class='col-8'><b>ココで使えるカード</b>&ensp;<i class='fas fa-angle-double-right p-0'>&ensp;</i></span>" 
                + "<div class='col-4 px-0'><a href="+ c_link[i] +" target='_blank'>" + c_name[i] + "</a></div>";
            }
        }else{
            return "<span class='col-8'><b>ココで使えるカード</b>&ensp;<i class='fas fa-angle-double-right p-0'>&ensp;</i></span>" 
            + "<div class='col-4 px-0'><a href="+ c_link +" target='_blank'>" + c_name + "</a></div>";
        }

    }
 
</script>
<script src="{{ asset('/js/modal_open.js') }}"></script>
<script src="{{ asset('/js/elevator.js') }}"></script>
<script src="{{ asset('/js/aco_open.js') }}"></script>
<script src="https://maps.googleapis.com/maps/api/js?language=ja&region=JP&key=AIzaSyBAEY8ljaq0u8SXzKNz_M2GGKGahHJYpAo&callback=initMap&libraries=places" async defer></script>
@endsection
