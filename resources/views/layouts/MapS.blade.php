<div id="maps-container" class="container mx-auto mt-5">
    <h2 id="maps-h2" class="text-center mb-5">ご近所検索</h2>
    <div class="row justify-content-center">
        <div class="form-group" id="maps-form">
            <input type="text" id="map_search" name="map_search" class="text-center border-warning mr-2" 
            placeholder="お住まいの地域・店名" aria-label="お住まいの地域・店名" aria-describedby="basic-addon1" 
            style="width: 22rem; height: 4rem;" data-toggle="tooltip"/>
            {{-- 初期表示 --}}
            <input type="hidden" id="h_lat" value="{{ $lat }}" />
            <input type="hidden" id="h_lng" value="{{ $lng }}" />
            {{-- modalクリック時 --}}
            <input type="hidden" id="h_request_flag" value="{{ $request_flag }}" />
            <input id="kensaku-map" class="btn kensaku-btn fas mt-1" type="button" value="&#xf002; 検索" 
            data-toggle="modal" data-target="#list_modal" data-backdrop="true" 
            style="width: 150px; height: 55px;" />
            <a href="{{ url('/') }}" id="reset-btn" class="btn btn-lg" 
            role="button">
                <span class="material-icons-outlined align-middle">restart_alt</span>
                <small class="font-weight-bold pt-1">RESET</small>
            </a>
        </div>
        
    </div>
    <div id="map" class="mt-3">
        <div class="row justify-content-center">
            <div id="map_convas" class="col-md-10" style="width:600px; height:550px;"></div>
        </div>
    </div>
</div>