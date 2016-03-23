@extends('articles.index')

@section('h1')

	<h1> {{ $num }} {{$num == 1 ? 'result' : 'results' }} for '{{ $query }}': </h1>
	
@stop