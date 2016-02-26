@extends('layouts.app')

@section('content')

	@yield('h1')
	<hr>
	<table>
	@foreach ($articles as $article)
	<tr><td>
		<h2><a href="/articles/{{ $article->id }}">{{ $article->title }}</a></h2>
		<div class ="body">{!! html_entity_decode(str_limit($article->body, 1000)) !!}</div>
		<a href="/articles/{{ $article->id }}"><button class="btn btn-primary">Read More</button></a>
	</td></tr>
	@endforeach
	</table>
	<br>
	{!! $articles->links() !!}
	
@stop