<div class="container">
<h2 class="contact-h2 text-center mb-3">お問い合わせ内容確認</h2>
    {{-- page.send_Pは、web.phpのnameメソッドが由来 --}}
    <form method="POST" action="{{ route('page.thanks_P') }}">
        @csrf
        <p>
            <label class="mt-5">■ メールアドレス</label><br>
            {{ $inputs['email'] }}
            <input name="email" value="{{ $inputs['email'] }}" type="hidden" />
        </p>
        <p>
            <label class="mt-3">■ タイトル</label><br>
            {{ $inputs['title'] }}
            <input name="title" value="{{ $inputs['title'] }}" type="hidden" />
        </p>
        <p>
            <label class="mt-3">■ お問い合わせ内容</label><br>
            {!! nl2br(e($inputs['body'])) !!}
            <input name="body" value="{{ $inputs['body'] }}" type="hidden" />
        </p>
        <button type="submit" name="action" value="back"
        class="contact-back btn my-5">
            入力内容修正
        </button>
        <button type="submit" name="action" value="submit"
        class="contact-btn btn my-5">
            送信する
        </button>
    </form>
</div>

