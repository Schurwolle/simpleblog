@extends('layouts.app')

@section('sides')
	@include('leftandright')
@endsection

@section('content')

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
	
@stop