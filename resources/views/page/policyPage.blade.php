@extends('layouts.layout')
@extends('layouts.header')
@extends('layouts.footer')


@section('title', 'Privacy Poricy')

@section('layouts.content')
<style>
    .board{
        display: none;
    }
</style>

{{-- topページと違い、content-mainとcontent-containerには"height: auto;をブレード内で採用している --}}
<div id="content-main" class="mx-auto">
    <div id="content_row" class="row">
        <div id="content-container" class="card mt-3 mx-auto col-lg-8">
            <div class="container">
                <div id="lens-set" class="col-lg-12 d-flex justify-content-center" style="top: -120%;">
                    <i id="lens" class="fas fa-circle fa-sm"></i>
                </div>
            </div>
            <div id="smart-dis" class="card-body bg-light p-5 my-5 mx-2">
                @include('layouts.policy')
            </div>
            <div class="container">
                <div class="col-lg-12 d-flex justify-content-center" style="top: 30%;">
                    <button type="button" class="btn bg-light smart-button"></button>
                </div>
            </div>
        </div>
    </div>
    <a href="{{ url('/') }}" class="history_back"><i class="fas fa-home fa-5x" data-toggle="backtip"></i></a>
</div>
<script src="{{ asset('/js/elevator.js') }}"></script>  
@endsection
