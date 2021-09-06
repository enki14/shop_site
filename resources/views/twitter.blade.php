@foreach($ad as $data)
<h4>{{ $data->shop_name }}</h4>
  @foreach($data->messages as $message)
  <ul>
      <li>{{ $message->text }}</li>
  </ul>
  @endforeach
@endforeach




{{-- h4の出力は[0]でも[1]でも[2]でもOK。所定の階層にnameがあればよい。 --}} 
{{--<h4>{{ $aeon[0]->user->name }}</h4>  
  <ul>
    @foreach($aeon as $data)
    <li>{{ preg_replace('/.*@[0-9a-zA-Z_]+\s/', '', strstr($data->text, 'http', true)) }}</li>
    @endforeach
  </ul>
<br><br>

<h4>{{ $seiyu[0]->user->name }}</h4>  
  <ul>
    @foreach($seiyu as $data)
    <li>{{ $data->text }}</li>
    @endforeach
  </ul>
<br><br>

<h4>{{ $itoyokado[0]->user->name }}</h4>  
  <ul>
    @foreach($itoyokado as $data)
    <li>{{ $data->text }}</li>
    @endforeach
  </ul>
<br><br>

<h4>{{ $daiei[0]->user->name }}</h4>  
  <ul>
    @foreach($daiei as $data)
    <li>{{ $data->text }}</li>
    @endforeach
  </ul>
<br><br>

<h4>{{ $donki[0]->user->name }}</h4>  
  <ul>
    @foreach($donki as $data)
    <li>{{ $data->text }}</li>
    @endforeach
  </ul>
<br><br>

<h4>{{ $summit[0]->user->name }}</h4>  
  <ul>
    @foreach($summit as $data)
    <li>{{ $data->text }}</li>
    @endforeach
  </ul>
<br><br>

<h4>{{ $tokyu[0]->user->name }}</h4>  
  <ul>
    @foreach($tokyu as $data)
    <li>{{ $data->text }}</li>
    @endforeach
  </ul>
<br><br>

<h4>{{ $olympic[0]->user->name }}</h4>  
  <ul>
    @foreach($olympic as $data)
    <li>{{ $data->text }}</li>
    @endforeach
  </ul>
<br><br>

<h4>{{ $ozamu[0]->user->name }}</h4>  
  <ul>
    @foreach($ozamu as $data)
    <li>{{ $data->text }}</li>
    @endforeach
  </ul>
<br><br>

<h4>{{ $superalps[0]->user->name }}</h4>  
  <ul>
    @foreach($superalps as $data)
    <li>{{ $data->text }}</li>
    @endforeach
  </ul>
<br><br>

<h4>{{ $waizumart[0]->user->name }}</h4>  
  <ul>
    @foreach($waizumart as $data)
    <li>{{ $data->text }}</li>
    @endforeach
  </ul>
<br><br>

<h4>{{ $belc[0]->user->name }}</h4>  
  <ul>
    @foreach($belc as $data)
    <li>{{ $data->text }}</li>
    @endforeach
  </ul>
<br><br>

<h4>{{ $shinanoya[0]->user->name }}</h4>  
  <ul>
    @foreach($shinanoya as $data)
    <li>{{ $data->text }}</li>
    @endforeach
  </ul>
<br><br>

<h4>{{ $foodway[0]->user->name }}</h4>  
  <ul>
    @foreach($foodway as $data)
    <li>{{ $data->text }}</li>
    @endforeach
  </ul>
<br><br>

<h4>{{ $hirai[0]->user->name }}</h4>  
  <ul>
    @foreach($hirai as $data)
    <li>{{ $data->text }}</li>
    @endforeach
  </ul>
<br><br>

<h4>{{ $kitamura[0]->user->name }}</h4>  
  <ul>
    @foreach($kitamura as $data)
    <li>{{ $data->text }}</li>
    @endforeach
  </ul>
<br><br>

<h4>{{ $sakae[0]->user->name }}</h4>  
  <ul>
    @foreach($sakae as $data)
    <li>{{ $data->text }}</li>
    @endforeach
  </ul>
<br><br>

<h4>{{ $FoodStoreAoki[0]->user->name }}</h4>  
  <ul>
    @foreach($FoodStoreAoki as $data)
    <li>{{ $data->text }}</li>
    @endforeach
  </ul>
<br><br>

<h4>{{ $hatijostore[0]->user->name }}</h4>  
  <ul>
    @foreach($hatijostore as $data)
    <li>{{ $data->text }}</li>
    @endforeach
  </ul>
<br><br>

<h4>{{ $yoshiike[0]->user->name }}</h4>  
  <ul>
    @foreach($yoshiike as $data)
    <li>{{ $data->text }}</li>
    @endforeach
  </ul>
<br><br>

<h4>{{ $amica[0]->user->name }}</h4>  
  <ul>
    @foreach($amica as $data)
    <li>{{ $data->text }}</li>
    @endforeach
  </ul>
<br><br>

<h4>{{ $sinsen[0]->user->name }}</h4>  
  <ul>
    @foreach($sinsen as $data)
    <li>{{ $data->text }}</li>
    @endforeach
  </ul>
<br><br>

<h4>{{ $biocebon[0]->user->name }}</h4>  
  <ul>
    @foreach($biocebon as $data)
    <li>{{ $data->text }}</li>
    @endforeach
  </ul>
<br><br>

<h4>{{ $kitanoAce[0]->user->name }}</h4>  
  <ul>
    @foreach($kitanoAce as $data)
    <li>{{ $data->text }}</li>
    @endforeach
  </ul>
<br><br>

<h4>{{ $n_azabu[0]->user->name }}</h4>  
  <ul>
    @foreach($n_azabu as $data)
    <li>{{ $data->text }}</li>
    @endforeach
  </ul>
<br><br>

<h4>{{ $NissinNWD[0]->user->name }}</h4>  
  <ul>
    @foreach($NissinNWD as $data)
    <li>{{ $data->text }}</li>
    @endforeach
  </ul>
<br><br>--}}