<div id="main-result" class="container py-4">
    <p class="pl-5">絞り込み結果：{{ $pagenate->total() }} 件　
        {{-- LengthAwarePaginatorのtotalメソッド --}}
    </p>
    <p class="pb-4 pl-5">絞り込み条件 : 
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
    </p>
@if(isset($pagenate))
    @foreach($pagenate as $data)
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div id="itiran-card" class="card mx-auto my-3">
                <div class="card-body rounded shadow">
                    @csrf
                    <div id="ribbon"><span id="new">new!!</span></div>
                    <div class="row">
                        <h5 class="col-md-12 text-center font-weight-bold mt-2">
                            @if(!empty($data->store_url))
                                <a href="{{ $data->store_url }}" target="_blank">
                                    {{ $data->shop_name }}{{ $data->store_name }}
                                </a>
                            @elseif(!empty($data->shop_url))
                                <a href="{{ $data->shop_url }}" target="_blank">
                                    {{ $data->shop_name }}
                                </a>
                            @elseif(empty($data->store_url) && empty($data->shop_url) && !empty($data->store_name))
                                {{ $data->shop_name }}{{ $data->store_name }}
                            @else
                                {{ $data->shop_name }}
                            @endif
                        </h5>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <img src="#" alt="テスト画像">
                        </div>
                    </div>
                    <div class="row">
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
        </div>    
    </div>
    @endforeach
@endif
    <div class="row">
        <div class="col-md-10">
            {{ $pagenate->appends($pagenate_params)->links() }} 
        </div>
    </div>
</div>