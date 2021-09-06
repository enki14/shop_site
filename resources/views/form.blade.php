<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" 
        rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <h2>ショップ情報の編集</h2>
        <div class="form-inline my-4">
            <form method="POST">
                @csrf
                <label for="new_name" class="me-2">新規作成</label>
                <input type="text" name="new_name" id="new_name" class="me-2" />
                <input type="button" class="ins_button" data-action="form_insert" value="新規" />
            </form>
            <form method="POST">
                @csrf
                <label for="up_select" class="mt-2">店名選択：</label>
                <select id="up_select" name="up_select">
                    <option value="">選択してください</option>
                    @foreach($list as $data)
                    <option value="{{ $data->shop_id }}">{{ $data->shop_name }}</option>
                    @endforeach
                </select>
                <input type="text" name="up_name" id="up_name" class="me-2" value="" />
                <input type="button" class="up_button" data-action="form_update" value="更新" /> 
            </form>
            <form method="POST">
                @csrf
                <label for="del_select" class="mt-2">店名選択：</label>
                <select id="del_select" name="del_select">
                    <option value="">選択してください</option>
                    @foreach($list as $data)
                    <option value="{{ $data->shop_id }}">{{ $data->shop_name }}</option>
                    @endforeach
                </select>
                <input type="button" class="del_button" data-action="form_delete" value="削除" /> 
            </form>
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
            $('.ins_button').click(function(){
                
                let name = $("#new_name").val();
                console.log(name);
                if(name === ''){
                    alert('新規の店名を入力してください');
                    $("[name=new_name]").css('background-color', '#fbefee');
                    return false;
                }
                let form = $(this).parents('form');
                form.attr('action', $(this).data('action'));
                $('<input>').attr({
                    'type':'hidden',
                    'name':'name',
                    'value': name,
                }).appendTo(form);
                
                form.submit();
                alert("「" + name + "」を追加しました")
                
            });
        
        
            <!-- formの更新にはページ遷移が必要 -->
            $('.up_button').click(function(){
                
                let id = $('#up_select option:selected').val();
                let text = $('[name=up_select] option:selected').text();
                let name = $('#up_name').val();
                
                if(id === ''){
                    alert('店名を選択してください');
                    return false;
                }
                
                if(name === ''){
                    alert('店名を入力してください');
                    $(".up_name").css('background-color', '#fbefee');
                    return false;
                }
                
                let form = $(this).parents('form');
                form.attr('action', $(this).data('action'));
                $('<input>').attr({
                    'type':'hidden',
                    'name':'id',
                    'value': id,
                }).appendTo(form);
                
                $('<input>').attr({
                    'type':'hidden',
                    'name':'name',
                    'value': name,
                }).appendTo(form);
                
                form.submit();    
                alert("「" + text + "」から「" + name + "」に変更しました")
                
            });
            
            
            $('.del_button').click(function(){
                
                let id_text = $('#del_select option:selected').text();
                let id = $('#del_select option:selected').val();
                
                if(confirm("本当に「"+ id_text +"」を削除しますか?") === false){
                    return false;
                }
                let form = $(this).parents('form');
                form.attr('action', $(this).data('action'));
                $('<input>').attr({
                    'type':'hidden',
                    'name':'id',
                    'value': id,
                }).appendTo(form);
                
                form.submit();
                alert("「"+ id_text +"」を削除しました");
                
            });
        });
    </script>
</body>



