@extends('layouts.layout')
@extends('layouts.header')
@extends('layouts.footer')



@section('title', 'スーパーマーケットのお得情報')
@section('description', 'スーパーマーケットのポイント情報や、セール情報が検索できるサイトです')

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
        <div id="content-container" class="card mt-3 bg-light mx-auto col-lg-8">
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
            eventClick: function(info){
                info.jsEvent.preventDefault();
                if(info.event._def.url){
                        window.open(info.event._def.url);
                }
                
            },
            eventDidMount: function(eventObj) {
                // var tooltip = new Tooltip(eventObj.el, {
                //     title: eventObj.event._def.title,
                //     url: eventObj.event._def.url,
                //     start: eventObj.event._instance.start,
                //     end: eventObj.event._instance.end,
                //     placement: 'top',
                //     trigger: 'hover',
                //     container: 'body'
                // });
                $('div.fc-event-title').tooltip({
                    title: eventObj.event._def.title,
                    url: eventObj.event._def.url,
                    start: eventObj.event._instance.start,
                    end: eventObj.event._instance.end,
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
