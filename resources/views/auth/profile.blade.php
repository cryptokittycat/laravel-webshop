@extends('layouts.app')
@section('title', 'Products')

@section('content')
<div class="col-md-8 col-md-offset-2">
  <h4>Name: {{ Auth::user()->name }}</h4>
  <p>Email: {{ Auth::user()->email }}</p>
  <p>Type: {{ Auth::user()->type }}</p>
  <p>Joined: {{ Auth::user()->created_at }}</p>
</div>
<div class="col-md-8 col-md-offset-2">
  <h3>Last 10 orders:</h3><br>
  <div id="order-list">
    @foreach($orders as $order)
      @php
        $arr = json_decode($order->details); 
        $total = 0;
      @endphp
      <div class="order">
        <h4>Order ID: #{{ $order->id }}</h4>
        <h4>Date: {{ $order->created_at }}</h4>
        <h4>Status: {{ $order->status }}</h4>
        <ul>
        <span>Name:</span><span>Size:</span><span>Color:</span><span>Amount:</span><span>Price:</span>
        </ul>
        @foreach($arr as $value)
          @php
            $item = json_decode($value);
            $total += $item->price;
          @endphp
          <ul>
            <span>{!! $item->name !!}</span>
            <span>{!! $item->size !!}</span>
            <span>{!! $item->color !!}</span>
            <span>{!! $item->amount !!}</span>
            <span>{!! $item->price !!}</span>
          </ul>
        @endforeach
      <h4>Total: {{ $total }}$</h4>
      </div><br>
    @endforeach
  </div>
</div>
@endsection