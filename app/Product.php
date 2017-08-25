<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Pictures;
use App\Category;
use App\Options;
use App\Values;
use Storage;

class Product extends Model
{

    public $timestamps = false;
    
    public function options() {
    	return $this->hasMany('App\Options');
    }

    public function values() {
    	return $this->hasMany('App\Values');
    }

    public function pictures() {
    	return $this->hasOne('App\Pictures');
    }

    public function category() {
    	return $this->hasOne('App\Category');
    }

    public function getDetails($options): void {
        if(in_array('thumb', $options)) {
            $this->thumb = Product::find($this->id)->pictures->thumb;
        }
        if(in_array('pic', $options)) {
            $this->path = Product::find($this->id)->pictures->path;
        }
        if(in_array('cat', $options)) {
            $this->cat = Category::select('category_name')
                                    ->where('id', $this->category_id)->get()[0]->category_name;
        }
        if(in_array('options', $options)) {
            $this->options = Values::select('option_value', 'option_id')
                                    ->where('product_id', $this->id)->get();
            foreach ($this->options as $key => $value) {
                $value->name = Options::select('name')
                                    ->where('id', $value->option_id)->get()[0]->name;
            }
        }
    }

}
