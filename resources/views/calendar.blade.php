<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <link href="{{ asset('css/fullcalendar.css') }}" rel="stylesheet">
        {{--<link href="../packages/core/main.css" rel="stylesheet" />
        <link href="../packages/daygrid/main.css" rel="stylesheet" />
        <link href="../packages/timegrid/main.css" rel="stylesheet" />
        <link href="../packages/list/main.css" rel="stylesheet" />--}}

        <script src="{{ asset('js/fullcalendar.js') }}" defer></script>
    </head>
    <body>
        <div id="calendar"></div>
    </body>
    <script>
        
    </script>
</html>