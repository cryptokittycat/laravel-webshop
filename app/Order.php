<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Order extends Model
{
    public static function getOrders($id) {
    	try {
    		if(Auth::user()->id == $id) {
		    	$orders = Order::where('user_id', $id)
		    				->orderBy('created_at', 'desc')
		    				->take(10)->get();
		    	return $orders;
		    }else {
		    	abort(404);
		    }
	    }catch(Exception $e) {
	    	abort(404);
	    }
    }
}
