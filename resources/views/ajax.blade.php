<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" 
        rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <h2>ショップ情報の編集</h2>
        <div class="form-inline my-4">
            @csrf
            <div class="ins_eria">
                <label for="new_name" class="me-2">新規作成</label>
                <input type="text" name="new_name" id="new_name" class="me-2" />
                <button type="button" id="btn_insert">新規</button>
            </div>
            @csrf
            <div class="up_eria">
                <label for="up_select" class="mt-2">店名選択：</label>
                <select id="up_select" name="up_select">
                    <option value="">選択してください</option>
                    @foreach($list as $data)
                    <option value="{{ $data->shop_id }}">{{ $data->shop_name }}</option>
                    @endforeach
                </select>
                <input type="text" name="up_name" class="up_name me-2" />
                <button type="button" class="btn_up">更新</button>
            </div>
            @csrf
            <div class="del_eria">
                <label for="del_select" class="mt-2">店名選択：</label>
                <select id="del_select" name="del_select">
                    <option value="">選択してください</option>
                    @foreach($list as $data)
                    <option value="{{ $data->shop_id }}">{{ $data->shop_name }}</option>
                    @endforeach
                </select>
                <button type="button" class="del_button">削除</button> 
            </div>    
        </div>
    </div>

    <div class="container mt-4">
        <h1>ショップ情報</h1>
    
        <ul id="s_p_info">
            @foreach($list as $data)
            <li>{{ $data->shop_name }}</li>
            @endforeach
        </ul>
    </div>
    
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function(){
            
            $("#btn_insert").click(function(){
                
                let name = $("#new_name").val();
                
                if(name === ''){
                    alert('新規の店名を入力してください');
                    $("#new_name").css('background-color', '#fbefee');
                    return false;
                }
            
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    data: {
                        name: name
                    },
                    url: "/ajax_insert",
                    dataType: "json",
                })
                .done(function(json){
                    console.log(json);
                    alert("新規登録に成功しました！！！");
                    setTimeout(function(){
                        window.location.href = "ajax";
                        
                    }, 500);
                })
                .fail(function(XMLHttpRequest, textStatus, errorThrown){
                    alert("新規登録時にエラーが発生しました：" + textStatus + ":\n" + errorThrown);
                });
            });
            
            
            
            $('.btn_up').on('click', function(){
                let id = $('[name=up_select]').val();
                console.log(id);
                let name = $('.up_name').val();
                
                if(id === ''){
                    alert('店名を選択してください');
                    return false;
                }
                
                if(name === ''){
                    alert('店名を入力してください');
                    $(".up_name").css('background-color', '#fbefee');
                    return false;
                }
            
            
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    data: {
                        id: id,
                        name: name
                    },
                    url: "/ajax_update",
                    dataType: "json",
                })
               .done(function(json){
                    console.log(json);
                    alert("更新に成功しました！！！");
                    setTimeout(function(){
                        window.location.href = "ajax";
                    }, 500);
                })
                .fail(function(XMLHttpRequest, textStatus, errorThrown){
                    alert("更新時にエラーが発生しました：" + textStatus + ":\n" + errorThrown);
                });
            });
            
                
            
            $('.del_button').on('click', function(){
        
                let id = $('#del_select').val();
                
                if(id === ''){
                    alert('店名を選択してください');
                    return false;
                }
                
                <!--選択されたものをselectedで指定できる-->
                let id_text = $('#del_select option:selected').text();
                
                if(confirm("本当に「"+ id_text +"」を削除しますか?") === false){
                    return false;
                }
                
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "GET",
                    data: {
                        id: id
                    },
                    url: "/ajax_delete",
                    dataType: "json",
                })
                .done(function(json){
                    console.log(json);
                    alert("削除に成功しました！！！");
                    setTimeout(function(){
                        window.location.href = "ajax";
                    }, 500);
                })
                .fail(function(XMLHttpRequest, textStatus, errorThrown){
                    alert("削除時にエラーが発生しました：" + textStatus + ":\n" + errorThrown);
                });
            });
            
        });
    </script>
</body>