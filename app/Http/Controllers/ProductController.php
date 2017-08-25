<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\Http\Middleware\isAdmin;

use App\Product;
use App\Category;
use App\Options;
use App\Values;
use App\Pictures;

use View;
use Validator;
use File;
use Image;
use Session;

class ProductController extends Controller
{
    /**
    * User authentication
    */
    public function __construct() {
        //check if user is both authenticated and has the role of admin
        $this->middleware('isAdmin')->except(['list', 'category', 'show', 'search']);
    }
    /**
     * Display a listing of the resource (admin).
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();
        $products = Product::orderBy('name', 'asc')->get();

        foreach ($products as $key => $product) {
            $product->getDetails(['thumb', 'cat', 'options']);
        }

        return view('product.index', ['products' => $products, 'categories' => $categories]);  
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::pluck('category_name');
        return view('product.create', ['categories' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make(Input::all(), [
            'name' => 'required|max:55',
            'description' => 'required|max:255',
            'price' => 'required|regex:/^\d{1,3}\.\d{2}$/',
            'cat_select' => 'required|max:20',
            'sizes' => 'required',
            'colors' => 'required',
            'images' => 'required|mimes:jpeg,jpg,png'
        ]); 

        if($validator->passes()) {

            /* check for duplicates */
            if( count(Product::where('name', 'LIKE', Input::get('name'))->get()) ) {
                $request->session()->flash('alert-danger', 'This item already exists.');
                return redirect()->back();
            }

            /* save product */
            $product = new Product;
            $product->name = Input::get('name');
            $product->description = Input::get('description');
            $product->price = Input::get('price');
            $product->slug = str_replace(' ', '_', $product->name);
            $product->category_id = Category::select('id')
                                            ->where('category_name', Input::get('cat_select'))
                                            ->get()[0]->id;
            $product->save();


            /* create/store thumbnail/picture and save */
            $image = Input::file('images');
            $picture = new Pictures;
            $picture->product_id = $product->id;

            $filename  = md5($image->getClientOriginalName() . microtime()) . '.' . $image->getClientOriginalExtension();

            $picture->path = 'images/' . $filename;
            $picture->thumb = 'images/thumbnails/thumb-' . $filename;
            
            Image::make($image->getRealPath())->save($picture->path);
            Image::make($image->getRealPath())
                    ->resize(150, 150)
                    ->save($picture->thumb);
            $picture->save();


            /* save options & values */
            $sizes = Input::get('sizes');
            $colors = Input::get('colors');

            foreach ($sizes as $key => $size) {
                $value = new Values;
                $value->product_id = $product->id;
                $value->option_id = Options::select('id')->where('name', 'size')->get()[0]->id;
                $value->option_value = $size;
                $value->save();
            }
            foreach ($colors as $key => $color) {
                $value = new Values;
                $value->product_id = $product->id;
                $value->option_id = Options::select('id')->where('name', 'color')->get()[0]->id;
                $value->option_value = $color;
                $value->save();
            }

            $request->session()->flash('alert-success', 'Item was successfully added!');
            return redirect()->back();
        }else {
            $messages = $validator->messages();
            $request->session()->flash('alert-danger', 'Failed to add item.');
            return redirect()->back()->with('errors', $messages);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $categories = Category::select('category_name')->get();
        $product = Product::where('id', $product->id)->get()[0];
        $product->getDetails(['pic', 'cat', 'options']);
        return view('product.edit', ['product' => $product, 'categories' => $categories]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $validator = Validator::make(Input::all(), [
            'name' => 'required|max:55',
            'description' => 'required|max:255',
            'price' => 'required|regex:/^\d{1,3}\.\d{2}$/',
            'cat_select' => 'required|max:20',
            'sizes' => 'required',
            'colors' => 'required',
            'images' => 'required|mimes:jpeg,jpg,png'
        ]); 

        if($validator->passes()) {
            $product = Product::find($product->id);
            $product->name = Input::get('name');
            $product->description = Input::get('description');
            $product->price = Input::get('price');
            $product->slug = str_replace(' ', '_', $product->name);
            $product->category_id = Category::select('id')
                                            ->where('category_name', Input::get('cat_select'))
                                            ->get()[0]->id;
            $product->save();

            $image = Input::file('images');
            $picture = Pictures::where('product_id', $product->id)->get()[0];

            $filename  = md5($image->getClientOriginalName() . microtime()) . '.' . $image->getClientOriginalExtension();

            /* delete old images */
            File::delete($picture->path);
            File::delete($picture->thumb);

            $picture->path = 'images/' . $filename;
            $picture->thumb = 'images/thumbnails/thumb-' . $filename;
            
            Image::make($image->getRealPath())->save($picture->path);
            Image::make($image->getRealPath())
                    ->resize(150, 150)
                    ->save($picture->thumb);
            $picture->save();

            $sizes = Input::get('sizes');
            $colors = Input::get('colors');

            Values::where('product_id', $product->id)->delete();        //delete former values
            foreach ($sizes as $key => $size) {
                $value = new Values;
                $value->product_id = $product->id;
                $value->option_id = Options::select('id')->where('name', 'size')->get()[0]->id;
                $value->option_value = $size;
                $value->save();
            }
            foreach ($colors as $key => $color) {
                $value = new Values;
                $value->product_id = $product->id;
                $value->option_id = Options::select('id')->where('name', 'color')->get()[0]->id;
                $value->option_value = $color;
                $value->save();
            }

            $request->session()->flash('alert-success', 'Item was successfully updated!');
            return redirect()->back();
        }else {
            $messages = $validator->messages();
            $request->session()->flash('alert-danger', 'Failed to edit item.');
            return redirect()->back()->with('errors', $messages);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $values = Values::where('product_id', $product->id)->delete();

        $picture = Pictures::where('product_id', $Product->id)->get()[0];
        File::delete($picture->path);
        File::delete($picture->thumb);
        $picture->delete();

        $product = Product::find($product->id);
        $product->delete();

        Session::flash('alert-success', 'Item was successfully deleted!');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $categories = Category::select('category_name')->get();
        $product = Product::where('slug', $slug)->get()[0];
        $product->getDetails(['pic', 'cat', 'options']);

        return view('product.details', ['product' => $product, 'categories' => $categories]);
    }
}
