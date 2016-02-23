@extends('articles.index')

@section('h1')

	<h1> Articles by {{ $articles->first()->user->name }} </h1>
	
@stop