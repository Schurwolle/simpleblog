@extends('layouts.app')

@section('content')

	@include('errors.list')

	<h1>Edit: {!! $article->title !!}</h1>

	{!! Form::model($article, ['method' => 'PATCH', 'url' => 'articles/'.$article->id, 'files' => 'true', 'data-parsley-validate' ] ) !!}

		@include('articles.form')

		@include('articles.submit', ['submitButton' => 'Update Article'])

	{!! Form::close() !!}


@stop