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
    <?php Log::debug($data->series_name); ?>
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="itiran-card card mx-auto my-3">
                <div class="card-body rounded shadow"> 
                    @csrf
                    {{-- イベントの登録日が１週間以内だったら「new!!」を表示 --}}
                    @if(!empty($data->register_day))
                        <?php 
                            $r_day = Common::hyphenFormat($data->register_day);  
                            $l_week = date("Y-m-d",strtotime("-1 week"));
                        ?>
                        @if($l_week <= $r_day)
                            <div class="ribbon"><span class="new">new!!</span></div>
                        @endif
                    @endif
                    <div class="row">
                        <h2 class="itiran_tenmei col-md-12 text-center font-weight-bold mt-4">
                            @if(!empty($data->series_id))
                                {{ $data->series_name }}
                            @elseif(!empty($data->store_url))
                                <a href="{{ $data->store_url }}" target="_blank" class="text-dark">
                                    {{ $data->shop_name }}{{ $data->store_name }}
                                </a>
                            @elseif(!empty($data->shop_url))
                                <a href="{{ $data->shop_url }}" target="_blank" class="text-dark">
                                    {{ $data->shop_name }}<small class="kakuten"> 各店</small>
                                </a>
                            @elseif(empty($data->store_url) && empty($data->shop_url) && !empty($data->store_name))
                                {{ $data->shop_name }}{{ $data->store_name }}
                            @else
                                {{ $data->shop_name }}<small class="kakuten"> 各店</small>
                            @endif
                        </h2>
                    </div>
                    <div class="row d-flex justify-content-center">
                        {{-- ロゴの使用許可があれば以下をitiran_imgを加える --}}
                        {{--<div class="col-md-4 mt-3">
                        @if(!empty($data->img_src))
                            <img src="{{ asset($data->img_src) }}" alt="テスト画像" class="itiran_img"/>
                        @else
                            <img src="{{ asset('/img/thumbnail-20200501_noimage.png') }}" alt="no_image" class="itiran_img"/>
                        @endif
                        </div>--}}
                        <div class="itiran_title col-md-10 pt-5"> 
                            @if(!empty($data->sp_url))
                                <h4 class="itiran_h4 card-title font-weight-bold mb-4">
                                    <a href="{{ $data->sp_url }}" target="_blank">{{ $data->sp_title }}</a>
                                </h4>
                            @else
                                <h4 class="itiran_h4 card-title font-weight-bold mb-4">
                                    {{ $data->sp_title }}
                                </h4>
                            @endif
                            <p class="itiran_pgh card-text pt-2">{{ $data->sp_subtitle }}</p>
                        </div>
                        <div class="container mt-2">
                            <div class="row">
                                <div class="col-11 d-flex justify-content-center">
                                @if(!empty($data->cash_kubun))
                                    <div class="row d-flex align-items-start my-4">
                                        <div class="col-2 arrow pt-2 mt-5">
                                            <i class="fas fa-reply fa-rotate-180 fa-2x" style="color: #B8860B;"></i>
                                        </div>
                                        <div class="col-9 pl-0 mt-3 itiran_cash">
                                            <span class="slash d-flex align-items-center position-relative font-weight-bold">
                                                対象カード
                                            </span>
                                            @if(strlen($data->cash_kubun) >= 50)
                                                <p class="sub-card-long mt-3 font-weight-bold">
                                                    {{ $data->cash_kubun }}
                                                </p>
                                            @else
                                                <p class="sub-card mt-3 font-weight-bold">
                                                    {{ $data->cash_kubun }}
                                                </p>
                                            @endif   
                                        </div>
                                    </div>
                                @endif
                                </div>
                            </div>
                            <p class="itiran_days card-text mt-3 pr-5 d-flex justify-content-end font-weight-bold">
                                @if(!empty($data->event_start) && !empty($data->event_end))
                                    開催日：{{ Common::dateFormat($data->event_start) }} ～ {{ Common::dateFormat_2($data->event_end) }}
                                @elseif(!empty($data->event_start) && empty($data->event_end))
                                    開催日：{{ Common::dateFormat($data->event_start) }}
                                @elseif(empty($data->event_start) && !empty($data->event_end))
                                    開催日：～ {{ Common::dateFormat_2($data->event_end) }}
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