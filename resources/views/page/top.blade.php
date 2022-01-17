@extends('layouts.layout')
@extends('layouts.header')
@extends('layouts.footer')



@section('title', 'スーパーマーケットのポイントカード情報♪　～東京都～')
@section('description', 'スーパーマーケットのポイントカードのお得な情報が検索できるサイトです♪♪')
{{-- カレンダー用モーダル --}}
<div id="calendarModal" class="modal fade" aria-hidden="true" style="display: none">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <span id="modal-date" class="text-muted mt-3 pl-2"></span>
            <div class="modal-header">
                <h5 id="modalShop" class="pl-2"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>    
                    <span class="sr-only">close</span>
                </button>
            </div>
            <div class="modal-body mb-4">
                <h4 id="modalTitle" class="modal-title pb-2 px-sm-3"></h4>
                <div id="modal_description" class="px-3 mb-3"></div>
                <div class="container mt-5">
                    <div class="row">
                        <span class="col-6"><b>ココで使えるカード</span>&ensp;<i class="fas fa-angle-double-right">&ensp;</i></span>
                        <div class="c_name col-6"></div>
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
<div id="content-main" class="mx-auto">
    <div id="content_row" class="row">
        <div id="content-container" class="card mt-3 mx-auto col-lg-8">
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
                console.log(info);
                let c_name = info.event._def.extendedProps.c_name;
                let c_link = info.event._def.extendedProps.c_link;
                console.log(c_link);
                let date = new Date(info.el.fcSeg.eventRange.range.start);
                let year = date.getFullYear();
                let month = date.getMonth() + 1;
                let day = date.getDate();
                // モーダルの日付表示
                $('#modal-date').html(month + '月' + day + '日');
                // モーダルのタイトルを追加
                $('#modalShop').html(info.event._def.title);
                // タイトルのセット(append()ではなくhtml()を使う)
                $('#modalTitle').html($('<a></a>', {href: info.event._def.url, target: "_blank"} ).text(info.event._def.extendedProps.main_title)); 
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
        if(c_name.match(/,/)){
            c_name = c_name.split(',');
            c_link = c_link.split(',');
            console.log(c_name);
            for(let i = 0; i < c_name.length; i++){
                return "<a href="+ c_link[i] +" target='_blank'>" + c_name[i] + "</a>";
            }
        }else{
            return "<a href="+ c_link +" target='_blank'>" + c_name + "</a>";
        }

    }
 
</script>
<script src="{{ asset('/js/modal_open.js') }}"></script>
<script src="{{ asset('/js/elevator.js') }}"></script>
<script src="https://maps.googleapis.com/maps/api/js?language=ja&region=JP&key=AIzaSyBAEY8ljaq0u8SXzKNz_M2GGKGahHJYpAo&callback=initMap&libraries=places" async defer></script>
@endsection
