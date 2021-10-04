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
        events: "http://localhost/shop_site/public/calendar_event_2",
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

function eventData(){
    
}