<div class="top-card card-img-top p-2">
    <h3 id="content-h3" class="text-center">お得を探す</h3>
    <div class="row justify-content-center">
        <form method="post" action="result" id="search-form">
            @csrf
            <div class="form-content form-group form-inline">
                <select class="search-select form-select border-success mr-2" aria-label="Default select example">
                    <option disabled selected value>エリア・駅</option>
                    <option value="1">One</option>
                    <option value="2">Two</option>
                    <option value="3">Three</option>
                </select>
                <select class="search-select form-select border-success mr-2" aria-label="Default select example">
                    <option disabled selected value>店名</option>
                    <option value="1">One</option>
                    <option value="2">Two</option>
                    <option value="3">Three</option>
                </select>
                <select id="search-schedule" name="search-schedule" class="search-select form-select border-success mr-2" aria-label="Default select example">
                    <option disabled selected value="0">日程</option>
                    <option value="1" @if($schedule == '1') selected @endif>今日・明日</option>
                    <option value="2" @if($schedule == '2') selected @endif>１週間</option>
                    <option value="3" @if($schedule == '3') selected @endif>今月</option>
                </select>
                <select class="search-select form-select border-success mr-2" aria-label="Default select example">
                    <option disabled selected value>商品ジャンル</option>
                    <option value="1">One</option>
                    <option value="2">Two</option>
                    <option value="3">Three</option>
                </select>
                <input type="text" id="search-shop" name="search-shop" class="keyword-input form-control border-success mr-2" 
                value="{{ $shop }}" placeholder="キーワード検索" aria-label="keyword" />
                <input type="submit" id="kensaku-main" 
                class="kensaku-btn btn" value="検索" data-toggle="popover"/>
            </div>
        </form>
    </div>
</div>
