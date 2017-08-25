@extends('layouts.app')
@section('title', $product->name)

@section('content')
<div class="row">
    <div class="col-md-10 col-md-offset-1 detail-container">
        <div class="col-md-4">
            <div class="detail-image">
                <img src="{{ url('/'.$product->path) }}" alt="{{ $product->name }}">
            </div>
        </div>
        <div>
            <div class="col-md-4">
                <h3>{{ $product->name }}</h3>
                <p>category: {{ $product->cat }}</p>
                <p>desc: {{ $product->description }}</p>
                <p>price: {{ $product->price }}</p>
            </div>
            <div class="col-md-4">
                <h4>Order:</h4>
                <br>
                <span>Size: </span>
                <select id="select-size" class="cs-select cs-skin-border">
                    @foreach($product->options as $key => $value)
                        @if($value->name == 'size')
                            <option value="{{ $value->option_value }}">{!! $value->option_value !!}</option>
                        @endif
                    @endforeach
                </select>
                <br>
                <span>Color: </span>
                <div id="color-select">
                    @foreach($product->options as $key => $value)
                        @if($value->name == 'color')
                            <a class="colorpick" href="#" style="color:{!! $value->option_value !!};background-color:{!! $value->option_value !!}"><span>{!! $value->option_value !!}</span></a>
                        @endif
                    @endforeach
                </div>
                <br>
                <span>Amount: </span>
                <p><input type="number" id="amount" name="amount" min="1" value="1"/></p>
                <input type="button" class="add-to-cart btn-primary" name="add-to-cart" value="Add to cart">
                <p id="item-data" data-item="{{ $product->name }}|{{ $product->price }}"></p>
            </div>
        </div>
    </div>
</div>
@endsection