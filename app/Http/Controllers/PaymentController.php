<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Order;
use App\Product;
use Session;
use Auth;

class PaymentController extends Controller
{
	private $APIKey;

	public function __construct() {
		$this->middleware('auth');
		$this->APIKey = "sk_test_uz45VvoUZoZifU8j7zvDckV2";
	}

	public function index() {
		return view('product/checkout');
	}

	public function chargeCard(Request $request) {
		\Stripe\Stripe::setApiKey($this->APIKey);

		// Token is created using Stripe.js
		// Get the payment token ID and products submitted by the form
		$token = $_POST['stripeToken'];
		$products = [];
		$total = 0;
		$description = '';

		foreach ($_POST['items'] as $value) {
			$data = json_decode($value);
			$product = Product::where('name', $data->name)->get()[0];

			$price = $data->amount * $product->price;
			$total += $price;

			array_push($products, $value);
		}

		$description = json_encode($products);

		$details = [
		"amount" => (int) ($total * 100),
		"currency" => "usd",
		"description" => $description,
		"source" => $token
		];

		// Charge the user's card
		try{
			$charge = \Stripe\Charge::create($details);

			$user = Auth::user();
			$order = new Order();
			$order->user_id = $user->id;
			$order->details = $description;
			$order->price = (int) ($total * 100);
			$order->last4card = $charge->source->last4;
			$order->address = $charge->source->address_zip;
			$order->save();

			Session::flash('alert-success', 'Successfully ordered items');
			return redirect()->back();
		}catch(Exception $e) {
			Session::flash('alert-danger', 'Error charging card.');
			return redirect()->back();
		}

	}
}
