@extends('layouts.app')

	@section('content')

	<h1>Write a new Article</h1>
	<hr>

	@include('errors.list')

	{!! Form::model($article = new \App\article, ['url' => 'articles', 'files' => 'true', 'data-parsley-validate' ]) !!}

		@include('articles.form')

		@include('articles.tagsImgs')

		@include('articles.publish')

		@include('articles.submit', ['submitButton' => '<i class="fa fa-plus"></i> Add Article'])


	{!! Form::close() !!}



	@endsection

