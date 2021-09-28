


import { Calendar } from '@fullcalendar/core'
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';
import momentTimezonePlugin from '@fullcalendar/moment-timezone';

document.addEventListener('DOMContentLoaded', function () {
  const calendarEl = document.getElementById('calendar');

  const calendar = new FullCalendar.Calendar(calendarEl, {
    allDaySlot: false,
    plugins: [dayGridPlugin, momentTimezonePlugin, interactionPlugin],
    timeZone: 'Asia/Tokyo', // momentTimezonePlugin
    initialView: 'dayGridMonth',
    height: 400,
    handleWindowResize: true,
    defaultDate: '2019-08-12',
    navLinks: true, 
    businessHours: true, 
    editable: true,
    events: [
      {
        title: 'Business Lunch',
        start: '2021-09-30',
        constraint: 'businessHours'
      }
    ],
    events: []
  });

  calendar.render();
});