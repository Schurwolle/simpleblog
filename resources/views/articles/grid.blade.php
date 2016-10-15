@extends('layouts.app')

@section('content')
	<title> Archive </title>
	<table style="border-spacing: 35px; border-collapse: separate;" align="center">
	<thead><tr><td style="vertical-align: bottom;"><h1>Articles</h1></td></tr></thead>
		@foreach($articles as $i => $article)
			@if($i % 3 == 0)
				<tr>
			@endif
			<td class="grid-td">
				<div>
				<h4 align="middle"><a class="black" href="/articles/{{ $article->slug }}/">{{$article->title}}</a></h4>
				<a href="/articles/{{$article->slug}}">{{ Html::image(('pictures/'.$article->id.'thumbnail'), null, ['class' => 'grid-image']) }}</a>
				</div>
			</td>
		@endforeach
	</table>
	<div align="center"> 
		{!! $articles->links() !!}
	</div>	
@endsection