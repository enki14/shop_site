<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>
        @section('styleCss')
            <link href="css/style.css" rel="stylesheet" type="text/css">
        @endsection
        <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.9.0/main.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.9.0/main.js" defer></script>
        <style>
            div.fc-event-title {
                white-space: normal;
            }

        </style>
    </head>
    <body>
        <div id="calendar" style="width: 900px;"></div>
    </body>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                        left: 'title',
                        center: 'dayGridMonth,listWeek',
                        right: 'prev,today,next'
                },
                events: [
                        {
                            id: '1',
                            title: 'event1',
                            start: '2021-09-07',
                            url: '#'
                        },
                        {
                            id: '2',
                            title: 'birth day!!',
                            start: '2021-10-14',
                            url: '#'
                        },
                        {
                            id: '3',
                            title: 'event3',
                            start: '2021-09-26',
                            end: '2021-09-30', 
                            url: '#'
                        }
                ],
                events: "http://localhost/shop_site/public/calendar_event",
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
</html>