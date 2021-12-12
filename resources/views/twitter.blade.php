@foreach($ad as $data)
<h4>{{ $data->shop_name }}</h4>
  @foreach($data->messages as $message)
  <ul>
      <li>{{ $message->text }}</li>
  </ul>
  @endforeach
@endforeach




