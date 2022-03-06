
@section('layouts.sidebar')
<div class="aco-eria card mt-3 col-4">
    <div class="aco-body card-body bg-light px-3 py-5 my-5">
        <div class="accordion" id="ac_parent">
            <div class="accordion-item">
                <h2 class="accordion-header aco-parent" id="headingOne">
                    <button class="btn font-weight-bold" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                        電子マネー
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-parent="#ac_parent">
                    <div class="accordion-body">
                        {{-- 動的にループ処理 --}}
                        <div class="accordion" id="ac_child">
                            <div class="accordion-item">
                                <h2 class="accordion-header my-4" id="eMoney-h">
                                    <button class="btn btn-link font-weight-bold text-decoration-none pl-4"
                                     type="button" data-toggle="collapse" data-target="#eMoney" 
                                     aria-expanded="false" aria-controls="eMoney">
                                        電子マネー⓵
                                    </button>
                                </h2>
                                <div id="eMoney" class="accordion-collapse collapse" aria-labelledby="eMoney-h" data-parent="#ac_child">
                                    <div class="accordion-body">
                                        ないよう
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header aco-parent" id="headingTwo">
                    <button class="btn font-weight-bold" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        クレジットカード
                    </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-parent="#ac_parent">
                    <div class="accordion-body">
                        {{-- 動的にループ処理 --}}
                        <div class="accordion" id="ac_child">
                            <div class="accordion-item">
                                <h2 class="accordion-header my-4" id="cCard-h">
                                    <button class="btn btn-link font-weight-bold text-decoration-none pl-4"
                                     type="button" data-toggle="collapse" data-target="#cCard"
                                      aria-expanded="false" aria-controls="cCard">
                                        クレジットカード⓵
                                    </button>
                                </h2>
                                <div id="cCard" class="accordion-collapse collapse" aria-labelledby="cCard-h" data-parent="#ac_child">
                                    <div class="accordion-body">
                                        ないよう
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header aco-parent" id="headingThree">
                    <button class="btn font-weight-bold" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        ポイントカード
                    </button>
                </h2>
                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-parent="#ac_parent">
                    <div class="accordion-body">
                        {{-- 動的にループ処理 --}}
                        <div class="accordion" id="ac_child">
                            <div class="accordion-item">
                                <h2 class="accordion-header my-4" id="pCard-h">
                                    <button class="btn btn-link font-weight-bold text-decoration-none pl-4"
                                     type="button" data-toggle="collapse" data-target="#pCard"
                                      aria-expanded="false" aria-controls="pCard">
                                        point card⓵
                                    </button>
                                </h2>
                                <div id="pCard" class="accordion-collapse collapse" aria-labelledby="pCard-h" data-parent="#ac_child">
                                    <div class="accordion-body">
                                        ないよう
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection