<div class="top-card card-img-top p-2">
    <h2 id="content-h2" class="text-center">お得を探す</h2>
    <div class="row justify-content-center">
        <form method="post" action="result" id="search-form">
            @csrf
            <div class="form-content form-group form-inline">
                <select id="search-schedule" name="search-schedule" class="search-schedule form-select border-success mr-3" 
                style="width: 10rem; height: 3.8rem;" aria-label="Default select example">
                    <option value="">-- 日程 --</option>
                    <option value="今日・明日" @if($schedule == '今日・明日') selected @endif>今日・明日</option>
                    <option value="１週間" @if($schedule == '１週間') selected @endif>１週間</option>
                    <option value="１ヵ月" @if($schedule == '１ヵ月') selected @endif>１ヵ月</option>
                </select>
                <input type="text" id="search-shop" name="search-shop" class="form-control input-lg border-success mr-3" 
                value="{{ $shop }}" placeholder="店名・地域など" aria-label="店名・地域検索" style="width: 22rem; height: 3.8rem;" />
                <input type="submit" id="kensaku-main" class="kensaku-btn btn fas" 
                value="&#xf002; 検索" data-toggle="popover" style="width: 10rem; height: 3.6rem;" />
            </div>
        </form>
    </div>
</div>
<hr class="horizon">

