@section('layouts.footer')
<footer class="footer align-items-center" style="height: 150px">
    <div id="footer-eria" class="d-flex justify-content-center pt-3">
        <div class="footer-main mr-4 pt-1">
            <a href="{{ url('/profile') }}" class="footer-link">
                <b class="text-center">プロフィール</b>
                <p class="text-center">Profile</p>
            </a>
        </div>
        <div class="footer-main mr-4 pt-1">
            <a href="{{ url('/contact') }}" class="footer-link">
                <b class="text-center">お問い合わせ</b>
                <p class="text-center">Contact</p>
            </a>
        </div>
        <div class="footer-main pt-1">
            <a href="{{ url('/policy') }}" class="footer-link">
                <b class="text-center">プライバシーポリシー</b>
                <p class="text-center">Privacy Policy</p>
            </a>
        </div>
    </div>
    <p class="d-flex justify-content-end mr-5 mt-4 font-weight-bold">Copyright © 2022 ポイントカード情報局</p>
</footer>
@endsection