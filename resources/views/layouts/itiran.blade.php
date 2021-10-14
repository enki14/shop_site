<div id="main-result" class="container py-4">
    <h4 class="pl-5 my-lg-5">絞り込み条件 : 
        @if(isset($schedule) or isset($shop))
            @if(!empty($schedule) && !empty($shop))
                {{ $schedule }} / {{ $shop }}
            @elseif(!empty($schedule) && empty($shop))
                {{ $schedule }}
            @else
                {{ $shop }}
            @endif
        @elseif(isset($keyword))
            @if(!empty($keyword))
                {{ $keyword }}
            @endif
        @endif
    </h4>
@if(isset($pagenate))
    @foreach($pagenate as $data)
    <div id="itiran-card" class="card mx-auto my-3" style="width: 50.5rem;">
        <div class="card-body rounded shadow">
            @csrf
            <div id="ribbon"><span id="new">new!!</span></div>
            <div class="row">
                <h5 class="col-md-12 text-center font-weight-bold mt-2">
                    <a href="@if(!empty($data->shop_url)){{ $data->shop_url }}@endif" target="_blank">
                        {{ $data->shop_name }}{{ $data->store_name }}
                    </a>
                </h5>
            </div>
            <div class="row g-0 mx-auto">
                <div class="col-md-4">
                    <img src="#" alt="テスト画像">
                </div>
                <div class="col-md-8"> 
                    <p class="card-text">お支払方法：○○××▢▢</p>
                    <p class="card-text">
                        @if(!empty($data->event_start) && !empty($data->event_end))
                            期間：{{ Common::dateFormat($data->event_start) }} ～ {{ Common::dateFormat($data->event_end) }}
                        @elseif(!empty($data->event_start) && empty($data->event_end))
                            期間：{{ Common::dateFormat($data->event_start) }}
                        @elseif(empty($data->event_start) && !empty($data->event_end))
                            期間：～ {{ Common::dateFormat($data->event_end) }}
                        @endif
                    </p>
                    <h5 class="card-title">{{ $data->sp_title }}</h5>
                    <p class="card-text">{{ $data->sp_subtitle }}</p>
                    <p class="card-text">
                    @if(!empty($data->sp_url))
                        <a href="{{ $data->sp_url }}" target="_blank">
                            <small class="text-muted">詳しくはこちら</small>
                        </a>
                    @endif
                    </p>
                </div>
            </div>
        </div>
    </div>    
    @endforeach
@endif
{{ $pagenate->appends($pagenate_params)->links() }} 
</div>