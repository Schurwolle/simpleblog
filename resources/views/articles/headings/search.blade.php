@extends('articles.index')

@section('h1')

	{{ $num }} {{$num == 1 ? 'result' : 'results' }} for '{{ $query }}': 
	
@stop