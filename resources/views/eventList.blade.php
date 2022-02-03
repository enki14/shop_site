<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>
    <body>
        <div style="margin-top: 100px;">
        @foreach($seiyu as $data)
            @if(!empty($data->el_id == 1))
                <h4>{{ $data->el_name }}</h4>
            @endif
            <ul>
                @if(!empty($data->el_title))
                    <li>
                        <a href="{{ $data->link }}" target="_blank">{{ $data->ocr_text }}</a>
                        <small>{{ $data->el_title }}</small>
                        <small style="color: #d2691e;">登録日：{{ $data->register_day }}</small>
                    </li>
                @endif
            </ul>
        @endforeach
        </div>
        <div style="margin-top: 100px;">
            <h4>メガドンキのイベ一覧</h4>
            @foreach($donki as $data)
            <ul>
                @if(!empty($data->el_title))
                    <li class="mt-3">
                        <a href="{{ $data->link }}" target="_blank">{{ $data->el_title }}</a><br/>
                        <small>{{ $data->el_subtitle }}</small>
                        <small style="color: #d2691e;">登録日：{{ $data->register_day }}</small>
                    </li>
                @endif
            </ul>
        @endforeach
        </div>
        <div style="margin-top: 100px;">
            <h4>イオンのイベ一覧</h4>
            @foreach($aeon as $data)
            <ul>
                @if(!empty($data->el_title))
                    <li class="mt-3">
                        <a href="{{ $data->link }}" target="_blank">{{ $data->el_title }}</a><br/>
                        <small>{{ $data->el_subtitle }}</small>
                        <small style="color: #d2691e;">登録日：{{ $data->register_day }}</small>
                    </li>
                @endif
            </ul>
            @endforeach
        </div>
        <div style="margin-top: 100px;">
            <h4>ヨークのイベ一覧</h4>
            @foreach($york as $data)
            <ul>
                @if(!empty($data->el_title))
                    <li class="mt-3">
                        <a href="{{ $data->link }}" target="_blank">{{ $data->el_title }}</a><br/>
                        <small>{{ $data->el_subtitle }}</small>
                        <small style="color: #d2691e;">登録日：{{ $data->register_day }}</small>
                    </li>
                @endif
            </ul>
            @endforeach
        </div>
        <div style="margin-top: 100px;">
            <h4>成城石井のイベ一覧</h4>
            @foreach($seizyo as $data)
            <ul>
                @if(!empty($data->el_title))
                    <li class="mt-3">
                        <a href="{{ $data->link }}" target="_blank">{{ $data->el_title }}</a><br/>
                        <small style="color: #d2691e;">登録日：{{ $data->register_day }}</small>
                    </li>
                @endif
            </ul>
            @endforeach
        </div>
            
    </body>
</html>