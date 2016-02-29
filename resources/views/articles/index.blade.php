@extends('layouts.app')

@section('content')

	@yield('h1')
	{!! $articles->links() !!}
	<hr>
	<table>
	@if($articles->count() > 0)
		@foreach ($articles as $article)
		<tr><td>
			<h2><a href="/articles/{{ $article->slug }}">{{ $article->title }}</a></h2>
			<div class ="body">{!! html_entity_decode(str_limit($article->body, 1000)) !!}</div>
			<a href="/articles/{{ $article->slug }}"><button class="btn btn-primary">Read More</button></a>
			<hr>
		</td></tr>
		@endforeach
	@else
		<h3>There are no articles at the moment.</h3>
	@endif
	</table>
	<br>
	{!! $articles->links() !!}
	
@stop