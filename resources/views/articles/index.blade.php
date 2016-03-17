@extends('layouts.app')

@section('head')
	<link href="/jquery.bxslider.css" rel="stylesheet" />
@endsection

@section('sides')
	@include('leftandright')
@endsection

@section('content')
    <div class="center">
	    <ul class="bxslider">
	    	@if($toparticles->count() > 0)
	        	@foreach($toparticles as $toparticle)
	        		@if (file_exists('pictures/'.$toparticle->id))
		      			<a href="/articles/{{$toparticle->slug}}"><li>{{ Html::image('pictures/'.$toparticle->id, null, ['title' => $toparticle->title]) }}</li></a>
		      		@endif
	        	@endforeach
	        @endif
	    </ul>
	</div>
	@yield('h1')
	{!! $articles->links() !!}

	<table>
	@if($articles->count() > 0)
		@foreach ($articles as $article)
			<tr>
				<td>
					<h2><a style="font-weight: bold;color: black;" href="/articles/{{ $article->slug }}">{{ $article->title }}</a></h2>
				</td>
			</tr>
			<tr>
				<td>{!! \Illuminate\Support\Str::words(html_entity_decode($article->body), 80) !!}</td>
			</tr>
			<tr>
				<td>
					@if (file_exists('pictures/'.$article->id))
						<article><a href="/articles/{{ $article->slug }}"> 
							{{ Html::image(('pictures/'.$article->id)) }}
						</a></article>
						<br>
					@else
						<a href="/articles/{{ $article->slug }}"><button class="btn btn-primary">Read More</button></a>
					@endif
				</td>
			</tr>
			<tr><td><hr></td></tr>
		@endforeach
	@else
		<h3>There are no articles at the moment.</h3>
	@endif
	</table>
	<br>
	{!! $articles->links() !!}	
@endsection

@section('footer')
<script src="/jquery.bxslider.min.js"></script>
<script>
	$(document).ready(function(){
	  $('.bxslider').bxSlider({
		  auto: true,
		  autoControls: true,
		  captions: true,
		  randomStart: true,
		  autoHover: true,
		  useCSS: false
		});
	});
</script>
@endsection