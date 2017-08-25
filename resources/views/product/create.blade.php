@extends('layouts.app')
@section('title', 'controls')

@section('content')
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        {!! Form::open(['url' => 'product', 'files' => true]) !!}
            {{ csrf_field() }}
            <div class="form-group">
                {{ Form::label('name', 'Name:') }}
                {{ Form::text('name') }}
            </div>
            <div class="form-group">
                {{ Form::label('description', 'Description:') }}
                {{ Form::text('description') }}
            </div>
            <div class="form-group">
                {{ Form::label('price', 'Price:') }}
                {{ Form::text('price') }}
            </div>
            <div class="form-group">
                {{ Form::label('category', 'Category:') }}
                <select name="cat_select">
                @foreach($categories as $key => $value) 
                    <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
                </select>
            </div>
            <div class="col-md-8 option-select">
                <div class="col-md-6 option-select">
                    {{ Form::label('size', 'Size:') }}
                    <div class="form-group form-group-list">
                        {{ Form::label('size', 'Shirts:') }}
                        <hr>
                        {{ Form::label('size', 'S') }}
                        {{ Form::checkbox('sizes[]', 'Small') }}
                        
                        {{ Form::label('size', 'M') }}
                        {{ Form::checkbox('sizes[]', 'Medium') }}
                        <br>
                        {{ Form::label('size', 'L') }}
                        {{ Form::checkbox('sizes[]', 'Large') }}
                        
                        {{ Form::label('size', 'XL') }}
                        {{ Form::checkbox('sizes[]', 'Extra Large') }}
                        <hr>
                        {{ Form::label('size', 'Shoes:') }}
                        <hr>
                        @for($i = 8;$i <= 11;$i++)
                            {{ Form::label('size', $i) }}
                            {{ Form::checkbox('sizes[]', $i) }}
                            {{ Form::label('size', $i+0.5) }}
                            {{ Form::checkbox('sizes[]', $i+0.5) }}
                            <br>
                        @endfor
                    </div>
                </div>
                <div class="col-md-6">
                    {{ Form::label('colors', 'Color:') }}
                    <div class="form-group form-group-list">
                        {{ Form::label('colors', 'blue') }}
                        {{ Form::checkbox('colors[]', 'blue') }}
                        <br>
                        {{ Form::label('colors', 'black') }}
                        {{ Form::checkbox('colors[]', 'black') }}
                        <br>
                        {{ Form::label('colors', 'red') }}
                        {{ Form::checkbox('colors[]', 'red') }}
                        <br>
                        {{ Form::label('colors', 'green') }}
                        {{ Form::checkbox('colors[]', 'green') }}
                        <br>
                        {{ Form::label('colors', 'yellow') }}
                        {{ Form::checkbox('colors[]', 'yellow') }}
                        <br>
                        {{ Form::label('colors', 'white') }}
                        {{ Form::checkbox('colors[]', 'white') }}
                        <br>
                        {{ Form::label('colors', 'brown') }}
                        {{ Form::checkbox('colors[]', 'brown') }}
                        <br>
                        {{ Form::label('colors', 'gray') }}
                        {{ Form::checkbox('colors[]', 'gray') }}
                        <br>
                        {{ Form::label('colors', 'purple') }}
                        {{ Form::checkbox('colors[]', 'purple') }}
                        <br>
                    </div>
                </div>
            </div>
            <br>
            <div class="form-group col-md-8">
                {{ Form::label('images', 'Images') }}
                {{ Form::file('images', ['multiple' => true]) }}
            </div>
            <div class="col-md-8">
            {{ Form::submit('Add product', ['class' => 'btn btn-primary']) }}
            </div>
        {!! Form::close() !!}
    </div>

    @include('errors.errorview')

</div>
@endsection