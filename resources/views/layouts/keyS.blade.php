<div class="container keys-container py-2">
    <h2 id="keys-h2" class="my-xl-5 text-center">話題・キーワード</h2>
    <form method="get" action="result-2" id="keys-form">
        @csrf
        {{-- ボタンにもそれぞれのvalue値が必要、かつ１つのformに統一された一意のname値 --}}
        <button type="submit" id="key_1" name="keyword" value="味覚" class="btn b-style m-3">秋の味覚</button>
        <button type="submit" id="key_2" name="keyword" value="秋分" class="btn b-style m-3">秋分</button>
        <button type="submit" id="key_3" name="keyword" value="台湾カステラ" class="btn b-style m-3">台湾カステラ</button>
        <button type="submit" id="key_4" name="keyword" value="地産地消" class="btn b-style m-3" >地産地消</button>
        <button type="submit" id="key_5" name="keyword" value="新規オープン" class="btn b-style m-3">新規オープン</button>
        <button type="submit" id="key_6" name="keyword" value="閉店セール" class="btn b-style m-3">閉店セール</button>
        <button type="submit" id="key_7" name="keyword" value="ネット販売" class="btn b-style m-3">ネット販売</button>
        <button type="submit" id="key_8" name="keyword" value="キャッシュレス" class="btn b-style m-3">キャッシュレス</button>
        <button type="submit" id="key_9" name="keyword" value="月末" class="btn b-style m-3">月末</button>
    </form>
</div>
<hr class="horizon">