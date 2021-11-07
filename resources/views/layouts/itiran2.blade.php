@if(count($shop_list) > 0)
<div id="map-data" class="map-data container py-4">
    {{-- $shop_list[0]は、モーダルをクリックしたときの店名そのもの --}}
    <p class="pl-5">絞り込み結果：{{ count($shop_list) }} 件</p>
    <p class="pb-4 pl-5">絞り込み条件：
    @if(isset($shop_list))
        @if($shop_list[0]->prefectures_name)
            {{ $shop_list[0]->prefectures_name }}{{ $shop_list[0]->town_name }}{{ $shop_list[0]->ss_town_name }} 付近
        @else($shop_list[0]->shop_name)
            {{ $shop_list[0]->shop_name }}{{ $shop_list[0]->store_name }}
        @endif
    @endif
    </p>
    @foreach($shop_list as $data)
<div class="row justify-content-center">
    <div class="col-md-10">
        <div id="itiran-card" class="card mx-auto my-3">
            <div id="card_body" class="card-body rounded shadow">
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
                        @if(!empty($data->sp_url))
                            <a href="{{ $data->sp_url }}" target="_blank">
                                <small class="text-muted">詳しくはこちら</small>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    @endforeach    
</div>
@endif