@section('layouts.footer')
<footer class="footer align-items-center" style="height: 150px">
    <div id="footer-eria" class="d-flex justify-content-center pt-3">
        <div class="footer-main mr-4 pt-1 text-center">
            <a href="{{ url('/profile') }}" class="footer-link">
                <b>プロフィール</b>
                <p>Profile</p>
            </a>
        </div>
        <div class="footer-main mr-4 pt-1 text-center">
            <a href="{{ url('/contact') }}" class="footer-link">
                <b>お問い合わせ</b>
                <p>Contact</p>
            </a>
        </div>
        <div class="footer-main mr-4 pt-1 text-center">
            <a href="{{ url('/disclaimer') }}" class="footer-link">
                <b>免責事項</b>
                <p>Disclaimer</p>
            </a>
        </div>
        <div class="footer-main pt-1 text-center">
            <a href="{{ url('/policy') }}" class="footer-link">
                <b>プライバシーポリシー</b>
                <p>Privacy Policy</p>
            </a>
        </div>
    </div>
    <p class="d-flex justify-content-end mr-5 mt-4 font-weight-bold">Copyright © 2022 ポイント王国</p>
</footer>
@endsection