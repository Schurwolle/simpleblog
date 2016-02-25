@extends('layouts.app')

@section('content')

	@include('errors.list')

	<h1>Edit: {!! $article->title !!}</h1>

	{!! Form::model($article, ['method' => 'PATCH', 'url' => 'articles/'.$article->id, 'files' => 'true', 'data-parsley-validate' ] ) !!}

		@include('articles.form')

		<div class="form-group">
		<label for="remove">Remove Previously Uploaded Image?</label>
		<input type="checkbox" name="remove">
		</div>

		@include('articles.submit', ['submitButton' => '<i class="fa fa-plus"></i> Update Article'])

	{!! Form::close() !!}


@stop
