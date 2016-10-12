@extends('layouts.app')

@section('sides')
	@include('leftandright')
@endsection

@section('content')
	<table>
	<tr>
		@foreach($articles as $chunk)
			<tr>
				@foreach($chunk as $article)
					<td style="padding: 10px;">
						<h3 align="middle"><a class="black" href="/articles/{{ $article->slug }}/">{{$article->title}}</a></h1>
						<a href="/articles/{{$article->slug}}">{{ Html::image(('pictures/'.$article->id.'thumbnail'), null, ['class' => 'grid-image']) }}
					</td>
				@endforeach
			</tr>
		@endforeach
	</table>

@endsection