@extends('layouts.app')

@section('content')

	@include('errors.list')

	<h1>Edit: {!! $article->title !!}</h1>

	{!! Form::model($article, ['method' => 'PATCH', 'url' => 'articles/'.$article->slug, 'files' => 'true', 'data-parsley-validate' ] ) !!}

		@include('articles.form')

		@if (count($addImgs) > 0)
			{!!Form::label('table' ,'Select images you want to delete:')!!}
			<table>
				<tr>
					@foreach($addImgs as $addImg)
					<td style="padding: 10px;">
						<a href="/{{$addImg}}" data-lightbox="lightbox2">{{HTML::image($addImg, null, ['style' => 'max-width:100px; max-height:100px; border: 3px solid #1468af;'])}}</a>
						{{ Form::checkbox('delete['.substr($addImg, strlen($addImg)-1, 1).']') }}
					</td>
					@endforeach
				</tr>
			</table>
		@endif

		@include('articles.submit', ['submitButton' => '<i class="fa fa-plus"></i> Update Article'])

	{!! Form::close() !!}


@stop
