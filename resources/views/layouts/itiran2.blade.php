<div class="container py-4">
    <h4 class="py-3 pl-5">絞り込み条件</h4>
    <p class="py-3 pl-5">○○駅 / ○○店 / 本日・明日</p>
@if(isset($sch))
    @foreach($sch as $data)
    <div class="card mx-auto rounded-3" style="width: 50.5rem;">
        <div class="card-body shadow">
            @csrf
            <div id="ribbon"><span id="new">new!!</span></div>
            <div class="row">
                <h5 class="col-md-12 text-center font-weight-bold mt-2">{{ $data->shop_name }}</h5>
            </div>
            <div class="row g-0 mx-auto">
                <div class="col-md-4">
                    <img src="#" alt="テスト画像">
                </div>
                <div class="col-md-8"> 
                    <p class="card-text">お支払方法：○○××▢▢</p>
                    <p class="card-text">期間：{{ $data->event_start }} ～ {{ $data->event_end }}</p>
                        <h5 class="card-title">{{ $data->sp_title }}</h5>
                        <p class="card-text">
                            ・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・
                            ・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・
                            ・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・
                        </p>
                    <p class="card-text"><a href="#"><small class="text-muted">詳しくはこちら</small></a></p>
                </div>
            </div>
        </div>
    </div>    
    @endforeach
@endif
{{ $sch->appends($params)->links() }} 
</div>