@foreach($products as $value)
<div class="col-md-3 col-sm-6 item-feature">
	<div class="thumbnail product-list-thumb">
		<a href="/product/{{ $value->slug }}">
			<img src="{{ url('/'.$value->thumb) }}" alt="">
		</a>
	    <div class="caption">
	        <a href="/product/{{ $value->slug }}">
	        	<h4>{{ $value->name }}</h4>
	        </a>
	        <p>
	            <a href="/product/{{ $value->slug }}" class="btn btn-primary">${{ $value->price }}</a> <a href="#" class="btn btn-default">{{ $value->cat }}</a>
	        </p>
	    </div>
	</div>
</div>
@endforeach