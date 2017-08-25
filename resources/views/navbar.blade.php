<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="/">Home</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li><a href="/products">products</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">

        @if (Auth::check())
          @if (Auth::user()->type == 'admin')
            <li class="admin-controls">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Controls<span class="caret"></span></a>
              <ul id="admin-controls-menu" class="dropdown-menu">
                <li>
                  <ul><a href="/product">Dashboard</a></ul>
                </li>
              </ul>
            </li>
          @endif
          <ul class="nav navbar-nav">
            <li><a href="/profile/{{ Auth::user()->id }}">Profile</a></li>
          </ul>
          <li>
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
          </li>
        @else
          <li><a href="/login">Login</a></li>
          <li><a href="/register">Register</a></li>
        @endif
        <li class="shoppingcart">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-shopping-cart fa-2x" aria-hidden="true"></i><span id="cart-counter" class="caret"></span></a>
          <ul id="shoppingcart-menu" class="dropdown-menu">
            <li>
              <div class="cart-items">
                <span>Name</span><span>Size</span><span>Color</span><span>Amount</span><span>Price ($)</span></div>
              <div id="shoppingcart-items"></div>
            </li>
          </ul>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>