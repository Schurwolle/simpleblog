@extends('articles.index')

@section('h1')

	<h1> Unpublished articles by {{ $articles->first()->user->name }} </h1>
	
@stop