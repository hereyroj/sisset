@foreach ($posts as $post)
<div class="carousel-item col-md-2 @if($loop->first) active @endif">
	<div class="card">
		<img src="{{ asset($post->cover_image) }}" alt="{{$post->title}}" class="card-img-top" alt="{{$post->title}}">
		<div class="card-body">
			<h5 class="card-title">{{$post->title}}</h5>
			<p class="card-text"><small class="text-muted">{{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $post->published_date)->format('F j, Y')}} | {{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $post->published_date)->diffForHumans()}}</small>| <a class="noticia_link" href="posts/{{$post->hasCategoria->slug}}"><small class="text-muted">{{$post->hasCategoria->name}}</small></a></p>
			<a href="/posts/{{$post->hasCategoria->slug}}/{{$post->slug}}" class="btn btn-primary">Leer más</a>
		</div>
	</div>
</div>
@endforeach 