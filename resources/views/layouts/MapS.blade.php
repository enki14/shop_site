<div id="maps-container" class="container mx-auto mt-5">
    <h3 class="maps-h3 text-center">ご近所検索</h3>
    <div class="row justify-content-center m-5">
        <div class="form-group">
            <input type="text" id="map_search" name="map_search" class="text-center border-warning mr-2" 
            placeholder="お住まいの地域・店名" aria-label="お住まいの地域・店名" aria-describedby="basic-addon1" 
            style="width: 20rem; height: 3.3rem;" data-toggle="tooltip" />
            <input type="hidden" id="h_lat" value="{{ $lat }}" />
            <input type="hidden" id="h_lng" value="{{ $lng }}" />
            <input id="kensaku-map" class="btn kensaku-btn" type="button" value="検索" 
            data-toggle="modal" data-target="#list_modal" data-backdrop="true" 
            style="width: 130px; height: 48px;" />
        </div>
    </div>
    <div id="map">
        <div class="row justify-content-center">
            <div id="map_convas" class="col-md-10" style="width:600px; height:550px;"></div>
        </div>
    </div>
</div>