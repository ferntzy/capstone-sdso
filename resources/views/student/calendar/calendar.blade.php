@php
  $container = 'container-xxl';
@endphp

@extends('layouts/contentNavbarLayout')

@section('title', 'Event Calendar (User)')

@section('vendor-style')
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.17/index.global.min.css" rel="stylesheet">
@endsection

@section('vendor-script')
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.17/index.global.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('content')
  <div class="{{ $container }} py-4">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
        <h5 class="mb-0">Event Calendar</h5>
      </div>
      <div class="card-body">
        <div id="calendar"></div>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const calendarEl = document.getElementById('calendar');
      let storedEvents = JSON.parse(localStorage.getItem('calendarEvents')) || [];

      const calendar = new FullCalendar.Calendar(calendarEl, {
        headerToolbar: {
          left: 'prev,next today',
          center: 'title',
          right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: storedEvents,
        selectable: false,
        editable: false,
        eventClick: function (info) {
          Swal.fire({
            title: info.event.title,
            html: `
                <b>Venue:</b> ${info.event.extendedProps.venue || 'N/A'}<br>
                <b>Time:</b> ${formatTime(info.event.start)} - ${formatTime(info.event.end)}
              `,
            confirmButtonText: 'Close'
          });
        }
      });

      calendar.render();

      function formatTime(dateObj) {
        if (!dateObj) return '';
        const d = new Date(dateObj);
        return d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
      }
    });
  </script>
@endsection