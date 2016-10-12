@extends('layouts.app')

@section('sides')
	@include('leftandright')
@endsection

@section('content')
	<table style="border-spacing: 15px; border-collapse: separate;">
	<tr>
		@foreach($articles as $chunk)
			<tr>
				@foreach($chunk as $article)
					<td class="grid-td">
						<h4 align="middle"><a class="black" href="/articles/{{ $article->slug }}/">{{$article->title}}</a></h4>
						<a href="/articles/{{$article->slug}}">{{ Html::image(('pictures/'.$article->id.'thumbnail'), null, ['class' => 'grid-image']) }}
					</td>
				@endforeach
			</tr>
		@endforeach
	</table>

@endsection