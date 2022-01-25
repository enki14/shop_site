@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="container">
                <h2>データベースの管理・編集</h2> 
                
                
                <div class="col text-left mt-5">
                    <!-- 取得したい店舗によってaction先を変える -->    
                    <form class="form-group" method="post" action="york">
                    @csrf
                        <button type="submit">ストア登録</button>
                    </form>

                    <!-- 取得したいイベントによってaction先を変える -->
                    <form class="form-group" method="post" action="keio_3times">
                    @csrf
                        <button type="submit">イベント登録</button>
                    </form>
                </div>
                
                <div class="form-inline my-4">
                    @csrf
                    <div class="ins_eria">
                        <label for="new_id" class="me-2">新規id :</label>
                        <input type="text" name="new_id" id="new_id" class="me-2" />
                        <label for="new_name" class="me-2">新規name :</label>
                        <input type="text" name="new_name" id="new_name" class="me-2" />
                        <label for="new_url" class="me-2">新規url :</label>
                        <input type="text" name="new_url" id="new_url" class="me-2" />
                        <label for="new_tui" class="me-2">新規twitter_user_id :</label>
                        <input type="text" name="new_tui" id="new_tui" class="me-2" />
                        <button type="submit" id="btn_insert">新規決定</button>
                    </div>
                    {{-- @csrf
                    <div class="container">
                        <div class="name_eria">
                            <select id="up_name" name="up_name">
                                <option value="">name選択</option>
                                @foreach($list as $data)
                                <option value="{{ $data->shop_id }}">{{ $data->shop_name }}</option>
                                @endforeach
                            </select>
                            <input type="text" name="up_ipt_name" class="up_ipt_name me-2" />
                            <button type="submit" class="btn_up_name">name更新</button>
                        </div>
                        <div class="url_eria">
                            <select id="up_url" name="up_url">
                                <option value="">url選択</option>
                                @foreach($list as $data)
                                <option value="{{ $data->shop_id }}">{{ $data->shop_url }}</option>
                                @endforeach
                            </select>
                            <input type="text" name="up_ipt_url" class="up_ipt_url me-2" />
                            <button type="submit" class="btn_up_url">url更新</button>
                        </div>
                        <div class="ut_eria">
                            <select id="up_ut" name="up_ut">
                                <option value="">usertimeline選択</option>
                                @foreach($list as $data)
                                <option value="{{ $data->shop_id }}">{{ $data->usertimeline }}</option>
                                @endforeach
                            </select>
                            <input type="text" name="up_ipt_url" class="up_ipt_url me-2" />
                            <button type="submit" class="btn_up_ut">usertimeline更新</button>
                        </div>
                    </div>
                    
                    @csrf
                    <div class="container">
                        <h4>削除</h4>
                        <select id="del_name" name="del_name">
                            <option value="">name削除</option>
                            @foreach($list as $data)
                            <option value="{{ $data->shop_id }}">{{ $data->shop_name }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn_del_name">name削除</button>
                        <select id="del_url" name="del_url">
                            <option value="">url削除</option>
                            @foreach($list as $data)
                            <option value="{{ $data->shop_id }}">{{ $data->shop_url }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn_del_url">url削除</button>
                        <select id="del_ut" name="del_ut">
                            <option value="">usertimeline削除</option>
                            @foreach($list as $data)
                            <option value="{{ $data->shop_id }}">{{ $data->usertimeline }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn_del_usertimeline">usertimeline削除</button>
                    </div> --}}
                        
                </div>
            </div>
            <div class="container mt-4">
                <h3>shop レコードデータ</h3>
                <p>id&ensp;/&ensp;name&ensp;/&ensp;url&ensp;/&ensp;twitter_user_id&ensp;/&ensp;api_scrape_kubun&ensp;/&ensp;img_src</p>    
                <ul id="s_p_info">
                    @foreach($list as $data)
                    <li>
                        {{ $data->shop_id }}&ensp;/&ensp;{{ $data->shop_name }}&ensp;/&ensp;
                        {{ $data->shop_url }}&ensp;/&ensp;{{ $data->twitter_user_id }}&ensp;/&ensp;{{ $data->img_src }}
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="container">
                <h2>スクレイピング用</h2>
                <form method="post" action="home_life">
                    @csrf
                    <button type="submit">life登録</button>
                </form>
            </div> 
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        
        $("#btn_insert").click(function(){
            
            let name = $("#new_name").val();
            let url = $("#new_url").val();
            let twitter_user_id = $("#new_tui").val();
            
            if(name === ''){
                alert('新規の店名を入力してください');
                $("#new_name").css('background-color', '#fbefee');
                return false;
            }

            // formの場合はname属性から値を取る。
            // ajaxの場合はデータのキーから値を取る。コントローラー側で$request->で呼び出せる
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                data: {
                    name: name,
                    url: url,
                    usertimeline: usertimeline
                },
                url: "home/insert",
                dataType: "json",
            })
            .done(function(json){
                console.log(json);
                alert("新規登録に成功しました！！！");
                setTimeout(function(){
                    window.location.href = "adm";
                    
                }, 500);
            })
            .fail(function(XMLHttpRequest, textStatus, errorThrown){
                alert("新規登録時にエラーが発生しました：" + textStatus + ":\n" + errorThrown);
            });
        });
        
        
        
        // $('.btn_up').on('click', function(){
        //     let id = $('[name=up_select]').val();
        //     console.log(id);
        //     let name = $('.up_name').val();
            
        //     if(id === ''){
        //         alert('店名を選択してください');
        //         return false;
        //     }
            
        //     if(name === ''){
        //         alert('店名を入力してください');
        //         $(".up_name").css('background-color', '#fbefee');
        //         return false;
        //     }
        
        
        //     $.ajax({
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         },
        //         type: "POST",
        //         data: {
        //             id: id,
        //             name: name
        //         },
        //         url: "/home_update",
        //         dataType: "json",
        //     })
        //    .done(function(json){
        //         console.log(json);
        //         alert("更新に成功しました！！！");
        //         setTimeout(function(){
        //             window.location.href = "home";
        //         }, 500);
        //     })
        //     .fail(function(XMLHttpRequest, textStatus, errorThrown){
        //         alert("更新時にエラーが発生しました：" + textStatus + ":\n" + errorThrown);
        //     });
        // });
        
            
        
        // $('.del_button').on('click', function(){
    
        //     let id = $('#del_select').val();
            
        //     if(id === ''){
        //         alert('店名を選択してください');
        //         return false;
        //     }
            
        //     <!--選択されたものをselectedで指定できる-->
        //     let id_text = $('#del_select option:selected').text();
            
        //     if(confirm("本当に「"+ id_text +"」を削除しますか?") === false){
        //         return false;
        //     }
            
        //     $.ajax({
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         },
        //         type: "GET",
        //         data: {
        //             id: id
        //         },
        //         url: "/home_delete",
        //         dataType: "json",
        //     })
        //     .done(function(json){
        //         console.log(json);
        //         alert("削除に成功しました！！！");
        //         setTimeout(function(){
        //             window.location.href = "home";
        //         }, 500);
        //     })
        //     .fail(function(XMLHttpRequest, textStatus, errorThrown){
        //         alert("削除時にエラーが発生しました：" + textStatus + ":\n" + errorThrown);
        //     });
        // });
        
    });
    </script>
@endsection
