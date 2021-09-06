@extends('layouts.layout')
@extends('layouts.header')
@extends('layouts.footer')



@section('title', 'スーパーマーケットのお得情報')
@section('description', 'スーパーマーケットのポイント情報や、セール情報が検索できるサイトです')




@section('layouts.content')
<div id="content-main" class="mx-auto">
    <div id="content-container" class="card mt-3 align-items-center  bg-light mx-auto" 
        style="height: auto; width: 65rem;">
        <div class="card-body">
           @include('layouts.search_value')
           @include('layouts.keyS')
            <div id="maps-container" class="container mx-auto">
                <h3 class="maps-h3 text-center">ご近所検索</h3>
                <form action="#" method="get">
                    <div id="map-form" class="form-group form-inline mx-auto mb-5">
                        <input tyep="text" id="search" class="keyword-input form-control text-center mr-2" value="スーパーマーケット検索" />
                        <select id="search-select" class="form-select mr-2" aria-label="Default select example">
                            <option selected>範囲</option>
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                        </select>
                        <select id="search-select" class="form-select mr-2" aria-label="Default select example">
                            <option selected>店名</option>
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                        </select>
                        <input class="kensaku-btn btn ml-2" type="button" value="検索" onClick="SearchGo()" />
                    </div>
                </form>    
                <div id="map" class="col-md">
                    <div id="map_convas" class="mx-auto" style="width:750px; height:562px;"></div>
                </div>
            </div>
        </div>        
        @include('layouts.itiran')
    </div>
</div>
@endsection
