@extends('layouts.app')

@section('content')
	<table>
	<tr>
		@foreach($articles as $chunk)
			<tr>
				@foreach($chunk as $article)
					<td>
						<h3 align="middle"><a class="black" href="/articles/{{ $article->slug }}/">{{$article->title}}</a></h1>
						<a href="/articles/{{$article->slug}}">{{ Html::image(('pictures/'.$article->id.'thumbnail'), null) }}
					</td>
				@endforeach
			</tr>
		@endforeach
	</table>

@endsection