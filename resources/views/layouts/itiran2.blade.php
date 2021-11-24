@if(count($shop_list) > 0)
<div id="map-data" class="map-data container py-4">
    {{-- $shop_list[0]は、モーダルをクリックしたときの店名そのもの --}}
    <p class="pl-5">絞り込み結果：{{ count($shop_list) }} 件</p>
    <p class="pb-4 pl-5">絞り込み条件：
        @if($s_flag == 'local')
            {{ $shop_list[0]->prefectures_name }}{{ $shop_list[0]->town_name }}{{ $shop_list[0]->ss_town_name }} 付近
        @else
            {{ $shop_list[0]->shop_name }}{{ $shop_list[0]->store_name }}
        @endif
    </p>
    
    @foreach($shop_list as $data)
    <div class="accordion" id="accordionExample{{ $data->shop_id }}">
        @if(!empty($data->shop_id) or !empty($data->store_id))
        <div class="card">
            <div class="card-header" id="headingOne{{ $data->shop_id }}">
                <h5 class="mb-0">
                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne{{ $data->shop_id }}" aria-expanded="true" aria-controls="collapseOne{{ $data->shop_id }}">
                        {{ $data->shop_name }}{{ $data->store_name }}
                        <span id="impact" class="py-sm-1 px-sm-1 ml-3">イベントあり</span>
                    </button>
                </h5>
            </div>
            <div id="collapseOne{{ $data->shop_id }}" class="collapse" aria-labelledby="headingOne{{ $data->shop_id }}" data-parent="#accordionExample{{ $data->shop_id }}">
                <div class="card-body">
                    <p>開催日：
                        @if(!empty($data->event_start) && !empty($data->event_end))
                            期間：{{ Common::dateFormat($data->event_start) }} ～ {{ Common::dateFormat($data->event_end) }}
                        @elseif(!empty($data->event_start) && empty($data->event_end))
                            期間：{{ Common::dateFormat($data->event_start) }}
                        @elseif(empty($data->event_start) && !empty($data->event_end))
                            期間：～ {{ Common::dateFormat($data->event_end) }}
                        @endif
                    </p>
                    @if(!empty($data->sp_url) or !empty($data->store_url))
                        <h4>
                            <a href="
                            @if(!empty($data->sp_url))
                                {{ $data->sp_url }}
                            @elseif(!empty($data->store_url))
                                {{ $data->store_url }}
                            @endif
                            ">{{ $data->sp_title }}</a>
                        </h4>
                    @else
                        <h4>{{ $data->sp_title }}</h4>
                    @endif
                    @if(!empty($data->sp_subtitle))
                        <p>{{ $data->sp_subtitle }}</p>
                    @endif
                </div>
            </div>
        </div>
        @else
        <div class="card">
            <div class="card-header d-flex align-items-center" id="headingTwo" style="height: 64.83px">
                <h5 class="mb-0 pl-sm-3">
                    <small>
                        {{ $data->shop_name }}{{ $data->store_name }}
                    </small>
                </h5>
            </div>
        </div>
        @endif
    </div>
    @endforeach
</div>
@endif