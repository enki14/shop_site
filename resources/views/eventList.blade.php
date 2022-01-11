<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>
    <body>
        <div class="pb-3">
            @if(isset($seiyu))
            {{--<?php dd($seiyu) ?>--}}
            
                <h4>西友</h4>
                
                <ul>
                @foreach($seiyu as $data)
                    @if(!empty($data->img_text) or !empty($data->text))
                        <li>
                            <a href="{{ $data->eventLink }}" target="_blank">{{ $data->img_text }}</a>
                            <small>{{ $data->text }}</small>
                        </li>
                    @endif
                @endforeach
                </ul>
            
            @endif
        </div>
            
    </body>
</html>