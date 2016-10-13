@extends('layouts.app')

@section('content')
	<title> Archive </title>
	<table style="border-spacing: 35px; border-collapse: separate;" align="center">
	<thead><td style="vertical-align: bottom;"><h1>Articles</h1></td></thead>
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