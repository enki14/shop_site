@extends('layouts.layout')
@extends('layouts.header')
@extends('layouts.footer')



@section('title', 'スーパーマーケットのお得情報')
@section('description', 'スーパーマーケットのポイント情報や、セール情報が検索できるサイトです')
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
{{-- カレンダー用モーダル --}}
<div id="calendarModal" class="modal fade" aria-hidden="true" style="display: none">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <span id="modal-date" class="text-muted mt-3 ml-3"></span>
            <div class="modal-header py-sm-0">
                <p id="modalShop"></p>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>    
                    <span class="sr-only">close</span>
                </button>
            </div>
            <div class="modal-body mb-4">
                <h4 id="modalTitle" class="modal-title pb-2"></h4>
                <div id="modal_description"></div>
            </div>
        </div>
    </div>
</div>
@section('layouts.content')
<div id="content-main" class="mx-auto">
    <div id="content_row" class="row">
        <div id="content-container" class="card mt-3 py-xl-3 bg-light mx-auto col-lg-8">
            <div class="card-body">
                @include('layouts.search_value')
                @include('layouts.keyS')
                @include('layouts.calendar_2')
                @include('layouts.MapS')
                @include('layouts.itiran2')
            </div>
        </div>
    </div>
    <a href="#" class="top_down"><i class="fas fa-cloud fa-5x" data-toggle="scroll_down"></i></a>
    {{--<a href="http://localhost/shop_site/public/" class="history_back"><i class="fas fa-chevron-circle-up fa-5x" data-toggle="scroll_top"></i></a>--}}
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                    left: 'prev,today,next',
                    center: 'title',
                    right: 'dayGridMonth,listWeek'
            },
            // あとはShopsiteController@eventCalendar_2メソッドでDBをレスポンスすればうまくやってくれる
            // indexメソッドの方には特に初期値など要らない
            events: "http://localhost/shop_site/public/eventCalendar_2",
            // eventClickやeventDidMountなどの関数は、それぞれのfc-event-titleにすでに機能している
            eventClick: function(info, jsEvent , view){
                info.jsEvent.preventDefault();
                // console.log(info);
                let date = new Date();
                let year = date.getFullYear(info.event.startStr);
                let month = date.getMonth(info.event.startStr) + 1;
                let day = date.getDate(info.event.startStr);
                // モーダルの日付表示
                $('#modal-date').html(year + '年' + month + '月' + day + '日');
                // モーダルのタイトルを追加
                $('#modalShop').html(info.event._def.title);
                // タイトルのセット(append()ではなくhtml()を使う)
                $('#modalTitle').html($('<a></a>', {href: info.event._def.url} ).text(info.event._def.extendedProps.main_title)); 
                // モーダルの本文をセット
                $('#modal_description').html(info.event._def.extendedProps.description);
                // モーダル着火
                $('#calendarModal').modal(); 
                
            },
            eventDidMount: function(eventObj) {
                console.log(eventObj.event._def.extendedProps.main_title);
                $('div.fc-event-title').tooltip({
                    title: eventObj.event._def.extendedProps.main_title,
                    trigger: 'hover',
                    placement: 'top',
                    container: 'body',
                    html: true
                });       
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
 
</script>
<script src="https://maps.googleapis.com/maps/api/js?language=ja&region=JP&key=AIzaSyBAEY8ljaq0u8SXzKNz_M2GGKGahHJYpAo&callback=initMap&libraries=places" async defer></script>
@endsection
