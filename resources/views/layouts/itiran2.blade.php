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
                        <h4 class="my-3 font-weight-bold itiran_h4">
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
                        <p class="itiran_pgh">
                            @if($data->shop_sale_title)
                                @if(!empty($data->shop_sale_subtitle))
                                    {{ $data->shop_sale_subtitle }}
                                @endif
                            @else
                                @if(!empty($data->store_sale_subtitle))
                                    {{ $data->store_sale_subtitle }}
                                @endif
                            @endif
                        </p>
                        <div id="conta_cash" class="container mt-5">
                            {{-- 親要素にd-flex align-items-startを加えることでflexboxの子要素同士の可変が可能 --}}
                            @if(!empty($data->shop_cashKubun) or !empty($data->store_cashKubun))
                                <div class="row d-flex align-items-start">
                                    <div class="col-2 arrow d-flex justify-content-end mt-5">
                                        <i class="fas fa-reply fa-rotate-180 fa-2x" style="color: #B8860B;"></i>
                                    </div>
                                    <div class="col-10 pl-0 itiran_cash">
                                        <span class="slash d-flex align-items-center position-relative font-weight-bold">
                                            対象カード
                                        </span>
                                        @if(!empty($data->shop_cashKubun))
                                            @if(strlen($data->shop_cashKubun) >= 50)
                                                <p class="sub-card-long mt-3">
                                                    <a href="{{ $data->link }}" target="_blank">{{ $data->shop_cashKubun }}</a>
                                                </p>
                                            @else
                                                <p class="sub-card mt-3">
                                                    <a href="{{ $data->link }}" target="_blank">{{ $data->shop_cashKubun }}</a>
                                                </p>
                                            @endif
                                        @else    
                                            @if(strlen($data->store_cashKubun) >= 50)
                                                <p class="sub-card-long mt-3">
                                                    <a href="{{ $data->link }}" target="_blank">{{ $data->store_cashKubun }}</a>
                                                </p>
                                            @else
                                                <p class="sub-card mt-3">
                                                    <a href="{{ $data->link }}" target="_blank">{{ $data->store_cashKubun }}</a>
                                                </p>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            @endif
                            <p class="itiran_days d-flex justify-content-end pt-3 pr-3">
                                @if($data->shop_sale_title)
                                    @if(!empty($data->shop_sale_eventStart) && !empty($data->shop_sale_eventEnd))
                                        開催日：{{ Common::dateFormat($data->shop_sale_eventStart) }} ～ 
                                        {{ Common::dateFormat_2($data->shop_sale_eventEnd) }}  
                                    @elseif(!empty($data->shop_sale_eventStart) && empty($data->shop_sale_eventEnd))
                                        開催日：{{ Common::dateFormat($data->shop_sale_eventStart) }}
                                    @elseif(empty($data->shop_sale_eventStart) && !empty($data->shop_sale_eventEnd))
                                        開催日：～ {{ Common::dateFormat_2($data->shop_sale_eventEnd) }}
                                    @endif
                                @else
                                    @if(!empty($data->store_sale_eventStart) && !empty($data->store_sale_eventEnd)) 
                                        開催日：{{ Common::dateFormat($data->store_sale_eventStart) }} ～ 
                                        {{ Common::dateFormat_2($data->store_sale_eventEnd) }}  
                                    @elseif(!empty($data->store_sale_eventStart) && empty($data->store_sale_eventEnd))
                                        開催日：{{ Common::dateFormat($data->store_sale_eventStart) }}
                                    @elseif(empty($data->store_sale_eventStart) && !empty($data->store_sale_eventEnd))
                                        開催日：～ {{ Common::dateFormat_2($data->store_sale_eventEnd) }}
                                    @endif
                                @endif
                            </p>
                        </div>
                        
                        
                        {{--<div class="row mt-5 mb-1">
                            <div class="col-8">
                                <div class="card-text">
                                    @if(isset($data->P_or_D))
                                        @if($data->P_or_D == 0)
                                        <div class="material-icons-outlined mb-3 mr-5">
                                            credit_score<span class="ml-1">ポイントが貯まる</span>
                                        </div>
                                        @elseif($data->P_or_D == 1)
                                        <div class="material-icons-outlined mb-3 mr-5">
                                            credit_score<span class="ml-1">割引がある</span>
                                        </div>
                                        @else
                                        <div class="material-icons-outlined mb-3 mr-5">
                                            credit_score<span class="ml-1">割引がある</span>
                                        </div>
                                        <div class="material-icons-outlined mb-3 mr-5">
                                            credit_score<span class="ml-1">ポイントが貯まる</span>
                                        </div>
                                        @endif
                                    @endif
                                </div>
                                <div class="card-text">
                                    @if(!empty($data->card_name))
                                        @if(preg_match('/,/', $data->card_name) == 1)
                                            <?php $c_name = explode(',', $data->card_name); ?>
                                            <?php $c_link = explode(',', $data->link); ?>
                                            <?php Log::debug($c_link); ?>
                                            <span>
                                            @for($i = 0; $i < count($c_name); $i++)
                                                <a href="{{ $c_link[$i] }}" class="cLink ml-4" target="_blank">{{ $c_name[$i] }}</a>
                                            @endfor
                                            </span>
                                        @else
                                            <span><a href="{{ $data->link }}" class="cLink ml-5" target="_blank">{{ $data->card_name }}</a></span>
                                        @endif
                                        
                                    @endif
                                </div>
                            </div>
                        </div>--}}
                        
                        
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