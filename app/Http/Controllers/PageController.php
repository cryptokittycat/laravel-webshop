<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Order;
use App\Product;
use App\Category;
use Illuminate\Pagination\LengthAwarePaginator;

class PageController extends Controller
{
	private static $page_lim = 16;

	/**
     * Home page
     *
     * @return \Illuminate\Http\Response
     */
	public function index() {
		$categories = Category::select('category_name')->get();
		$products = Product::all()->take(8);

		foreach ($products as $product) {
			$product->getDetails(['thumb', 'cat']);
		}

		return view('home', ['products' => $products, 'categories' => $categories] );
	}

    /**
     * Display user profile
     *
     * @return \Illuminate\Http\Response
     */
	public function profile($id) {
		$orders = Order::getOrders($id);

		return view('auth.profile', ['orders' => $orders]);
	}

    /**
     * Display a listing of the resource
     *
     * @return \Illuminate\Http\Response
     */
    public function list() {
    	$categories = Category::select('category_name')
    						->orderBy('category_name', 'asc')->get();
    	$products = Product::simplePaginate(self::$page_lim);

    	foreach ($products as $key => $product) {
    		$product->getDetails(['thumb', 'cat']);
    	}

    	return view('product.list', ['products' => $products, 'categories' => $categories]);
    }

    /**
     * Display product search results
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request) {
    	$cat = $request->route('cat');
    	$search = $request->route('search');

        $order = ['name', 'asc'];   //set default order
        switch($request->route('order')) {
        	case 'a-asc':
        	$order = ['name', 'asc'];
        	break;
        	case 'a-desc':
        	$order = ['name', 'desc'];
        	break;
        	case 'p-asc':
        	$order = ['price', 'asc'];
        	break;
        	case 'p-desc':
        	$order = ['price', 'desc'];
        	break;
        	default:
        }

        if($search === null || $search === '') {
        	if($cat === null) {
        		$products = Product::orderBy($order[0], $order[1])
        		->simplePaginate(self::$page_lim);
        	}else {
        		$id = Category::select('id')->where('category_name', $cat)->get()[0]->id;
        		$products = Product::where('category_id', $id)
        		->orderBy($order[0], $order[1])
        		->simplePaginate(self::$page_lim);
        	}
        }else {
        	if($cat === null) {
        		$products = Product::where('name', 'LIKE', '%'.$search.'%')->take(16)
        		->orderBy($order[0], $order[1])
        		->simplePaginate(self::$page_lim);
        	}else {
        		$id = Category::select('id')->where('category_name', $cat)->get()[0]->id;
        		$products = Product::where('name', 'LIKE', '%'.$search.'%')
        		->where('category_id', $id)
        		->orderBy($order[0], $order[1])
        		->simplePaginate(self::$page_lim);
        	}
        }
        foreach ($products as $key => $product) {
        	$product->getDetails(['thumb', 'cat']);
        }

        return view('product.tile', ['products' => $products]);
    }
}
