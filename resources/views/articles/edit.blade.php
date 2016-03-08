@extends('layouts.app')

@section('content')

	@include('errors.list')

	<h1>Edit: {!! $article->title !!}</h1>

	{!! Form::model($article, ['method' => 'PATCH', 'url' => 'articles/'.$article->slug, 'files' => 'true', 'data-parsley-validate' ] ) !!}

		@include('articles.form')

		@if (file_exists('pictures/'.$article->id) || file_exists('pictures/'.$article->id.'thumbnail'))
			<div class="form-group">
			<label for="remove">Remove Previously Uploaded Image(s)?</label>
			<input type="checkbox" name="remove">
			</div>
		@endif

		@include('articles.submit', ['submitButton' => '<i class="fa fa-plus"></i> Update Article'])

	{!! Form::close() !!}


@stop
