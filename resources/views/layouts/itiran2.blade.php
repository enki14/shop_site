@if(count($shop_list) > 0)
<div id="map-data" class="map-data container py-4">
    {{-- $shop_list[0]は、モーダルをクリックしたときの店名そのもの --}}
    <p class="pl-5">絞り込み結果：{{ count($shop_list) }} 件</p>
    <p class="pb-4 pl-5">絞り込み条件：
            {{ $shop_list[0]->shop_name }}{{ $shop_list[0]->store_name }}
    </p>
    @foreach($shop_list as $data)
    <div class="accordion" id="accordionExample{{ $data->shop_id }}_{{ $data->store_id }}">
        @if(!empty($data->se_title) or !empty($data->sh_title) or !empty($data->st_title))
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
                            @if($data->se_title)
                                @if(!empty($data->spse_url))
                                    <a href="{{ $data->spse_url }}">{{ $data->se_title }}</a>
                                @else
                                    {{ $data->se_title }}
                                @endif
                            @elseif($data->sh_title)
                                @if(!empty($data->spsh_url))
                                    <a href="{{ $data->spsh_url }}">{{ $data->sh_title }}</a>
                                @else
                                    {{ $data->sh_title }}
                                @endif
                            @else
                                @if(!empty($data->spst_url))
                                    <a href="{{ $data->spst_url }}">{{ $data->st_title }}</a>
                                @else
                                    {{ $data->st_title }}
                                @endif
                            @endif
                        </h4>
                        <p class="itiran_pgh">
                            @if(!empty($data->se_subtitle))
                                {{ $data->se_subtitle }}
                            @elseif(!empty($data->sh_subtitle))
                                {{ $data->sh_subtitle }}
                            @else
                                {{ $data->st_subtitle }}
                            @endif
                            
                        </p>
                        <div id="conta_cash" class="container mt-5">
                            {{-- 親要素にd-flex align-items-startを加えることでflexboxの子要素同士の可変が可能 --}}
                            @if(!empty($data->se_cash) or !empty($data->sh_cash) or !empty($data->st_cash))
                                <div class="row d-flex align-items-start">
                                    <div class="col-2 arrow d-flex justify-content-end mt-5">
                                        <i class="fas fa-reply fa-rotate-180 fa-2x" style="color: #B8860B;"></i>
                                    </div>
                                    <div class="col-10 pl-0 itiran_cash">
                                        <span class="slash d-flex align-items-center position-relative font-weight-bold">
                                            対象のお支払
                                        </span>
                                        @if(!empty($data->se_cash))
                                            @if(strlen($data->se_cash) >= 50)
                                                <p class="sub-card-long mt-3 font-weight-bold">
                                                    {{ $data->se_cash }}
                                                </p>
                                            @else
                                                <p class="sub-card mt-3 font-weight-bold">
                                                    {{ $data->se_cash }}
                                                </p>
                                            @endif
                                        @elseif(!empty($data->sh_cash))
                                            @if(strlen($data->sh_cash) >= 50)
                                                <p class="sub-card-long mt-3 font-weight-bold">
                                                    {{ $data->sh_cash }}
                                                </p>
                                            @else
                                                <p class="sub-card mt-3 font-weight-bold">
                                                    {{ $data->sh_cash }}
                                                </p>
                                            @endif
                                        @else    
                                            @if(strlen($data->st_cash) >= 50)
                                                <p class="sub-card-long mt-3 font-weight-bold">
                                                    {{ $data->st_cash }}
                                                </p>
                                            @else
                                                <p class="sub-card mt-3 font-weight-bold">
                                                    {{ $data->st_cash }}
                                                </p>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            @endif
                            <p class="itiran_days d-flex justify-content-end pt-3 pr-3 font-weight-bold">
                                @if(!empty($data->se_start))
                                    @if(!empty($data->se_start) && !empty($data->se_end))
                                        開催日：{{ Common::dateFormat($data->se_start) }} ～ 
                                        {{ Common::dateFormat_2($data->se_end) }}  
                                    @elseif(!empty($data->se_start) && empty($data->se_end))
                                        開催日：{{ Common::dateFormat($data->se_start) }}
                                    @elseif(empty($data->se_start) && !empty($data->se_end))
                                        開催日：～ {{ Common::dateFormat_2($data->se_end) }}
                                    @endif
                                @elseif(!empty($data->sh_start))
                                    @if(!empty($data->sh_start) && !empty($data->sh_end))
                                        開催日：{{ Common::dateFormat($data->sh_start) }} ～ 
                                        {{ Common::dateFormat_2($data->sh_end) }}  
                                    @elseif(!empty($data->sh_start) && empty($data->sh_end))
                                        開催日：{{ Common::dateFormat($data->sh_start) }}
                                    @elseif(empty($data->sh_start) && !empty($data->sh_end))
                                        開催日：～ {{ Common::dateFormat_2($data->sh_end) }}
                                    @endif
                                @else
                                    @if(!empty($data->st_start) && !empty($data->st_end)) 
                                        開催日：{{ Common::dateFormat($data->st_start) }} ～ 
                                        {{ Common::dateFormat_2($data->st_end) }}  
                                    @elseif(!empty($data->st_start) && empty($data->st_end))
                                        開催日：{{ Common::dateFormat($data->st_start) }}
                                    @elseif(empty($data->st_start) && !empty($data->st_end))
                                        開催日：～ {{ Common::dateFormat_2($data->st_end) }}
                                    @endif
                                @endif
                            </p>
                        </div>
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