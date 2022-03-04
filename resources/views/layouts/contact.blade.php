<div class="conta-container container">
    <h2 class="h2 contact-h2 text-center">お問い合わせフォーム</h2>
    <form method="POST" action="{{ route('page.confirm_P') }}">
        @csrf

        <label class="mt-5">メールアドレス</label>
        <input name="email" value="{{ old('email') }}" type="text" 
        class="contact-input form-control input-lg border-success" />
        @if ($errors->has('email'))
            <p class="error-message mt-3 text-danger font-weight-bold">{{ $errors->first('email') }}</p>
        @endif

        <label class="mt-3">タイトル</label>
        <input name="title" value="{{ old('title') }}" type="text" 
        class="contact-input form-control input-lg border-success" />
        @if ($errors->has('title'))
            <p class="error-message mt-3 text-danger font-weight-bold">{{ $errors->first('title') }}</p>
        @endif

        <label class="mt-3">お問い合わせ内容</label>
        <textarea name="body" class="form-control border-success" cols="20" rows="10">
            {{ old('body') }}
        </textarea>
        @if ($errors->has('body'))
            <p class="error-message mt-3 text-danger font-weight-bold">{{ $errors->first('body') }}</p>
        @endif

        <button type="submit" class="contact-btn btn mt-3 font-weight-bold">
            入力内容確認
        </button>
    </form>
</div>
