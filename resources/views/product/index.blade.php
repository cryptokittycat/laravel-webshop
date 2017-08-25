@extends('layouts.app')
@section('title', 'Products')

@section('content')
<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <div class="col-md-8">
            <div class="product-list">
            <table id="product-table" class="tablesorter">
            <thead>
                <tr>
                    <th>name</th>
                    <th>category</th>
                    <th>price</th>
                </tr>
            </thead>
            @foreach($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->cat }}</td>
                    <td>{{ $product->price }}</td>
                    <td><a href="{{ URL::to('product/' . $product->id . '/edit') }}">Edit</a>
                    <div>
                    {{ Form::open(array('url' => 'product/' . $product->id)) }}
                        {{ csrf_field() }}
                        {{ Form::hidden('_method', 'DELETE') }}
                        {{ Form::submit('Delete', array('class' => 'btn btn-warning btn-delete')) }}
                    {{ Form::close() }}
                    </div>
                    </td>
                </tr>
            @endforeach
            </table>
            </div>
        {{ Form::open(array('url' => 'product/create', 'method' => 'GET')) }}
        {{ Form::submit('Add Product', array('class' => 'btn btn-primary')) }}
        {{ Form::close() }}
        </div>
        <div class="col-md-4 category-list">
        @foreach($categories as $cat)
            <ul>
                <span>{{ $cat->category_name }}</span>
                <span>
                {{ Form::open(array('url' => 'product/category/' . $cat->id)) }}
                    {{ csrf_field() }}
                    {{ Form::hidden('_method', 'DELETE') }}
                    {{ Form::submit('Delete', array('class' => 'btn btn-warning')) }}
                {{ Form::close() }}
                </span>
            </ul>
        @endforeach
        <h3>Add:</h3>
        {!! Form::open(['url' => 'product/category']) !!}
            {{ csrf_field() }}
            {{ Form::label('name', 'Name:') }}
            {{ Form::text('name') }}
            {{ Form::submit('Add category', ['class' => 'btn btn-primary']) }}
        {!! Form::close() !!}
        </div>
    </div>

    @include('errors.errorview')
    
</div>
@endsection