@extends('layouts.app')
@section('title', 'Products')

@section('content')
<div id="payment-details">
  <h3>Your order:</h3>
</div>
<br>
<div class="col-md-6 col-md-offset-3">
  <form action="/charge" method="post" id="payment-form">
  <div id="payment-items">
    <span>Name:</span><span>Size:</span><span>Color:</span><span>Amount:</span><span>Price($):</span>
  </div>
  <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="form-row">
      <br>
      <label for="card-element">
        Credit or debit card
      </label>
      <div id="card-element">
        <!-- a Stripe Element will be inserted here. -->
      </div>

      <!-- Used to display Element errors -->
      <div id="card-errors" role="alert"></div>
    </div>

    <button id="order-btn" class="btn-primary btn-lg">Order</button>
  </form>
</div>
@endsection

@include('errors.errorview')

@section('scripts')
    <!-- stripe.js should always be loaded directly from https://js.stripe.com -->
    <script src="https://js.stripe.com/v3/"></script>
    {!!Html::script('js/payment.js')!!}
@endsection