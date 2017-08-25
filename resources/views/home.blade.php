@extends('layouts.app')
@section('title', 'Home')

@section('content')
<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <!-- Header -->
        <header class="jumbotron hero-spacer">
            <h1>Up to 70% off!</h1>
            <p>Check out our latest deals on Jeans</p><p>For a limited time 20-70% off on all designer jeans!</p>
            </p>
        </header>

        <hr>

        <!-- Title -->
        <div class="row">
            <div class="col-lg-12">
                <h3>Latest Features</h3>
            </div>
        </div>
        <!-- /.row -->

        <!-- Page Features -->
        <div class="row text-center">
        
        @foreach($products as $value)
            <div class="col-md-3 col-sm-6 item-feature">
                <div class="thumbnail">
                    <img src="{{ $value->thumb }}" alt="">
                    <div class="caption">
                        <h3>{{ $value->name }}</h3>
                        <p>
                            <a href="#" class="btn btn-primary">${{ $value->price }}</a>
                            <a href="/products#{{ $value->cat }}" class="btn btn-default">{{ $value->cat }}</a>
                        </p>
                    </div>
                </div>
            </div>
        @endforeach

        </div>
        <!-- /.row -->

    </div>
</div>
@endsection