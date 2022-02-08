<div class="container keys-container py-2">
    <h2 id="keys-h2" class="my-5 text-center">話題・キーワード</h2>
    <div class="row d-flex justify-content-center">
    @foreach($value as $data)
        <form method="get" action="result-2" id="keys-form">
            @csrf
            {{-- ShopsiteController@keyRes_value()からvalue値を取得 --}}
            <div class="col-2 px-0">
                <button type="submit" name="keyword" value="{{ $data->keyword }}" class="btn b-style m-3 p-2 font-weight-bold">
                    {{ $data->keyword }}
                </button>
            </div>
        </form>
    @endforeach
    </div>  
</div>
<hr class="horizon">