@if(count($shop_list) > 0)
<div id="map-data" class="map-data container py-4">
    {{-- $shop_list[0]は、モーダルをクリックしたときの店名そのもの --}}
    <p class="pl-5">絞り込み結果：{{ count($shop_list) }} 件</p>
    <p class="pb-4 pl-5">絞り込み条件：
            {{ $shop_list[0]->shop_name }}{{ $shop_list[0]->store_name }}
    </p>
    @foreach($shop_list as $data)
    <div class="accordion" id="accordionExample{{ $data->shop_id }}_{{ $data->store_id }}">
        @if(!empty($data->shop_sale_title) or !empty($data->store_sale_title))
            <div class="card">
                <div class="card-header" id="headingOne{{ $data->shop_id }}_{{ $data->store_id }}">
                    <h5 class="mb-0">
                        {{-- 「 _ 」の前と後ろでshop_idかstore_idかを判別している --}}
                        <button class="btn btn-link" type="button" data-toggle="collapse" 
                        data-target="#collapseOne{{ $data->shop_id }}_{{ $data->store_id }}" 
                        aria-expanded="true" aria-controls="collapseOne{{ $data->shop_id }}_{{ $data->store_id }}">
                            {{ $data->shop_name }}{{ $data->store_name }}
                        </button>
                        <span class="impact py-sm-1 px-sm-1 ml-3">イベントあり</span>
                    </h5>
                </div>
                <div id="collapseOne{{ $data->shop_id }}_{{ $data->store_id }}" class="collapse" 
                aria-labelledby="headingOne{{ $data->shop_id }}_{{ $data->store_id }}" 
                data-parent="#accordionExample{{ $data->shop_id }}_{{ $data->store_id }}">
                    <div class="card-body">
                        <p>
                        @if($data->shop_sale_title)
                            @if(!empty($data->shop_sale_eventStart) && !empty($data->shop_sale_eventEnd)) 
                                開催日：{{ Common::dateFormat($data->shop_sale_eventStart) }} ～ 
                                {{ Common::dateFormat($data->shop_sale_eventEnd) }}  
                            @elseif(!empty($data->shop_sale_eventStart) && empty($data->shop_sale_eventEnd))
                                開催日：{{ Common::dateFormat($data->shop_sale_eventStart) }}
                            @elseif(empty($data->shop_sale_eventStart) && !empty($data->shop_sale_eventEnd))
                                開催日：～ {{ Common::dateFormat($data->shop_sale_eventEnd) }}
                            @endif
                        @else
                            @if(!empty($data->store_sale_eventStart) && !empty($data->store_sale_eventEnd)) 
                                開催日：{{ Common::dateFormat($data->store_sale_eventStart) }} ～ 
                                {{ Common::dateFormat($data->store_sale_eventEnd) }}  
                            @elseif(!empty($data->store_sale_eventStart) && empty($data->store_sale_eventEnd))
                                開催日：{{ Common::dateFormat($data->store_sale_eventStart) }}
                            @elseif(empty($data->store_sale_eventStart) && !empty($data->store_sale_eventEnd))
                                開催日：～ {{ Common::dateFormat($data->store_sale_eventEnd) }}
                            @endif
                        @endif
                        </p>
                        <h4>
                            @if($data->shop_sale_title)
                                @if(!empty($data->shop_sale_spUrl))
                                    <a href="{{ $data->shop_sale_spUrl }}">{{ $data->shop_sale_title }}</a>
                                @else
                                    {{ $data->shop_sale_title }}
                                @endif
                            @else
                                @if(!empty($data->store_sale_spUrl))
                                    <a href="{{ $data->store_sale_spUrl }}">{{ $data->store_sale_title }}</a>
                                @else
                                    {{ $data->store_sale_title }}
                                @endif
                            @endif
                        </h4>
                        @if($data->shop_sale_title)
                            @if(!empty($data->shop_sale_subtitle))
                                <p>{{ $data->shop_sale_subtitle }}</p>
                            @endif
                        @else
                            @if(!empty($data->store_sale_subtitle))
                                <p>{{ $data->store_sale_subtitle }}</p>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        @else
            <div class="card">
                <div class="headingTwo card-header d-flex align-items-center" style="height: 64.83px">
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