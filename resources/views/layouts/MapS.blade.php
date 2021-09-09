<div id="maps-container" class="container mx-auto">
    <h3 class="maps-h3 text-center">ご近所検索</h3>
    <div class="row justify-content-center m-5">
        <div class="col-5">
            <input type="text" id="search" name="map_search" value="{{ $map_search }}" class="form-control text-center border-warning" 
            placeholder="お住まいの地域・店名" aria-label="お住まいの地域・店名" aria-describedby="basic-addon1"
            style="width: 20rem; height: 3.3rem;" data-toggle="tooltip" />
        </div>
        <div class="col-3">
            <input id="kensaku-map" class="btn kensaku-btn" type="button" value="検索" data-toggle="modal" data-target="#list_modal" data-backdrop="true"/>
        </div>
    </div>
    <div id="map" class="col-md">
        <div id="map_convas" style="width:750px; height:562px;"></div>
    </div>
</div>