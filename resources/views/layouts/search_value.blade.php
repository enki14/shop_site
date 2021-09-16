<div class="top-card card-img-top p-2">
    <h3 id="content-h3" class="text-center">お得を探す</h3>
    <div class="row justify-content-center">
        <form method="post" action="result" id="search-form">
            @csrf
            <div class="form-content form-group form-inline">
                <select id="search-schedule" name="search-schedule" class="search-select form-select border-success mr-2" 
                style="width: 160px; height: 45px;" aria-label="Default select example"
                >
                    <option value="">-- 日程 --</option>
                    <option value="1" @if($schedule == '1') selected @endif>今日・明日</option>
                    <option value="2" @if($schedule == '2') selected @endif>１週間</option>
                    <option value="3" @if($schedule == '3') selected @endif>今月</option>
                </select>    
                <select id="search-select" class="search-select form-select border-success mr-2" style="width: 180px; height: 45px;" 
                aria-label="Default select example">
                    <option value="">-- 商品ジャンル --</option>
                    <option value="1">One</option>
                    <option value="2">Two</option>
                    <option value="3">Three</option>
                </select>    
                <input type="text" id="search-shop" name="search-shop" class="form-control input-lg border-success mr-3" 
                value="{{ $shop }}" placeholder="店名・地域など" aria-label="keyword" style="width: 300px; height: 45px;" />
                <input type="submit" id="kensaku-main" 
                class="kensaku-btn btn" value="検索" data-toggle="popover" style="width: 150px; height: 45px;" />     
            </div>
        </form>
    </div>
</div>
