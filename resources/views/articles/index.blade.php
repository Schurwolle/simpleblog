@extends('layouts.app')

@section('content')

	@yield('h1')
	<hr>
	<table>
	@foreach ($articles as $article)
	<tr><td>
		<h2><a href="/articles/{{ $article->id }}">{{ $article->title }}</a></h2>
		<div class ="body">{!! html_entity_decode(str_limit($article->body, 1000)) !!}</div>
	</td></tr>
	@endforeach
	</table>
	{!! $articles->links() !!}
	
@stop