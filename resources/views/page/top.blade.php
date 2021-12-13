@extends('layouts.layout')
@extends('layouts.header')
@extends('layouts.footer')



@section('title', 'スーパーマーケットのポイントカード・クレジットカード情報♪　～東京都～')
@section('description', 'スーパーマーケットのポイントカード・クレジットカードのお得な情報が検索できるサイトです♪♪')
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
    <div class="d-flex">
        {{-- SNS関係のシェアボタン --}}
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
    <a href="#" class="top_down"><i class="fas fa-cloud fa-5x" data-toggle="scroll_down"></i></a>
    {{--<a href="http://localhost/shop_site/public/" class="history_back"><i class="fas fa-chevron-circle-up fa-5x" data-toggle="scroll_top"></i></a>--}}
</div>
@endsection
@section('resultScript')
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
            events: "eventCalendar_2",
            // eventClickやeventDidMountなどの関数は、それぞれのfc-event-titleにすでに機能している
            eventClick: function(info){
                info.jsEvent.preventDefault();
                console.log(info);
                let date = new Date(info.el.fcSeg.eventRange.range.start);
                let year = date.getFullYear();
                let month = date.getMonth() + 1;
                let day = date.getDate();
                // モーダルの日付表示
                $('#modal-date').html(year + '年' + month + '月' + day + '日');
                // モーダルのタイトルを追加
                $('#modalShop').html(info.event._def.title);
                // タイトルのセット(append()ではなくhtml()を使う)
                $('#modalTitle').html($('<a></a>', {href: info.event._def.url, target: "_blank"} ).text(info.event._def.extendedProps.main_title)); 
                // モーダルの本文をセット
                $('#modal_description').html(info.event._def.extendedProps.description);
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
 
</script>
<script src="{{ asset('/js/modal_open.js') }}"></script> 
{{--<script src="{{ asset('/js/map_search.js') }}"></script>--}}
<script src="https://maps.googleapis.com/maps/api/js?language=ja&region=JP&key=AIzaSyBAEY8ljaq0u8SXzKNz_M2GGKGahHJYpAo&callback=initMap&libraries=places" async defer></script>
@endsection
