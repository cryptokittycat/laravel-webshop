<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Webshop - @yield('title')</title>

    <!-- bootstrap/css -->
    {!!Html::style('css/bootstrap.min.css')!!}
    {!!Html::style('css/main.css')!!}
    {!!Html::style('css/font-awesome.css')!!}
</head>

<body>

    <div class="container navbar">
        @include('navbar')
    </div>

    <div class="container content">
        @yield('content')
    </div>

    <br>

    <div class="col-md-10 col-md-offset-1">
        <div class="flash-message">
          @foreach (['danger', 'warning', 'upload', 'info', 'success'] as $msg)
          @if(Session::has('alert-' . $msg))
          <p class="alert alert-{{ $msg }}">{!! Session::get('alert-' . $msg) !!}</p>
          @endif
          @endforeach
      </div>
  </div>

  <hr>

  <!-- Footer -->
  <div class="col-md-10 col-md-offset-1">
    <footer>
        <div class="row">
            <div class="col-lg-12">
                <p>Copyright &copy; Daan Stokhof 2017</p>
            </div>
        </div>
    </footer>
</div>

<!-- javascript -->
{!!Html::script('js/jquery.js')!!}
{!!Html::script('js/bootstrap.min.js')!!}
{!!Html::script('js/jquery.tablesorter.min.js')!!}
{!!Html::script('js/main.js')!!}
{!!Html::script('js/shoppingcart.js')!!}
@yield('scripts')
</body>
</html>