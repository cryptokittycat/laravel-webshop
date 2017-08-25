@extends('layouts.app')
@section('title', 'Products')

@section('content')
<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <div class="col-md-10 col-md-offset-1 product-list-control">
            <span>Filter:</span>
            <input type="textbox" id="searchbox" name="searchbox"/>
            <span>Order By:</span>
            <select id="order-by">
                <option value="a-asc">Alphabetically Asc.</option>
                <option value="a-desc">Alphabetically Desc.</option>
                <option value="p-asc">Price Asc.</option>
                <option value="p-desc">Price Desc.</option>
            </select>
        </div>
        <div class="col-md-3 product-list-sidebar">
            <h3>Categories:</h3>
            <ul class="selected"><a class="category_link" href="#" data-cat="">All</a></ul>
            @foreach($categories as $key => $value) 
                <ul><a id="cat_{{ $value['category_name'] }}" class="category_link" href="#{{ $value['category_name'] }}" data-cat="{{ $value['category_name'] }}">{{ $value['category_name'] }}</a></ul>
            @endforeach
        </div>
        <div class="col-md-9 product-list-content">
            <div id="searchcontent">
            @include('product.tile')
            </div>
        </div>
        <div class="col-md-4 col-md-offset-6 product-list-content">
            <div id="page-control">
                <div id="page-numbers">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection