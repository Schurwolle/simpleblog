@extends('layouts.app')

@section('content')

	@include('errors.list')

	<h1 class="articleTitle">Edit: {{ $article->title }}</h1>

	{!! Form::model($article, ['method' => 'PATCH', 'url' => 'articles/'.$article->slug, 'files' => 'true', 'data-parsley-validate' ] ) !!}

		@include('articles.form')

		@if (count($addImgs) > 0)
			{!!Form::label('table' ,'Select images you want to delete:')!!} <a href="#" id ="deleteinfo" title="First select images you want to delete(if any), then choose new ones."  onclick="return false"><i class="fa fa-info-circle"></i></a>
			<table class="additional">
				<tr>
					@foreach($addImgs as $addImg)
					<td align="middle";>
						<a href="/{{$addImg}}" data-lightbox="lightbox2">{{HTML::image($addImg)}}</a>
						<br>
						{{ Form::checkbox('delete['.explode('lb',$addImg)[1].']') }}
					</td>
					@endforeach
				</tr>
			</table>
		@endif

		@include('articles.tagsImgs')

		@include('articles.submit', ['submitButton' => '<i class="fa fa-plus"></i> Update Article'])

	{!! Form::close() !!}


@stop
