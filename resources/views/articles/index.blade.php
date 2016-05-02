@extends('layouts.app')

@section('head')
	<style>
		h1 {
		  text-align: center;
		}
	</style>
@endsection

@section('sides')
	@include('leftandright')
@endsection

@section('content')
    <div style="margin:0 auto;width: 100%;height: auto;">
	    <ul class="bxslider">
	    	@if($toparticles->count() > 0)
	        	@foreach($toparticles as $toparticle)
	        		@if (file_exists('pictures/'.$toparticle->id))
		      			<a href="/articles/{{$toparticle->slug}}"><li>{{ Html::image('pictures/'.$toparticle->id, null, ['title' => $toparticle->title]) }}</li></a>
		      		@endif
	        	@endforeach
	        @endif
	    </ul>
	</div>
	@yield('h1')
	@if(!isset($query))
		<div align="center"> 
		{!! $articles->links() !!}
		</div>	
	@endif
	<table>
		@if($articles->count() > 0)
			@foreach ($articles as $article)
				<tr>
					<td>
						<table width="100%">
							<tr valign="baseline">
								<td>
									<h2><a style="font-weight: bold;color: black;" href="/articles/{{ $article->slug }}">{!! $article->title !!}</a></h2>
								</td>
								<td align="right">
									<i class="fa fa-star{{ !$article->favoritedBy->contains(Auth::id()) ? '-o' : '' }}" style="color: gold;"></i> {{ $article->favoritedBy->count() }} 
									&nbsp
									<i class="fa fa-comment{{ !$article->hasCommentFromUser(Auth::id()) ? '-o' : '' }}" style="color: purple;"></i> {{ $article->comments->count() }}
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td align="justify">{!! \Illuminate\Support\Str::words(str_replace(array('<li>','</li>'),array('•','&nbsp'), strip_tags($article->body, '<a>,<h2>,<h3>,<h4>,<h5>,<strong><em><s><li><span>')), 80) !!}</td>
				</tr>
				<tr>
					<td>
						@if (file_exists('pictures/'.$article->id))
							<article><a href="/articles/{{ $article->slug }}"> 
								{{ Html::image('pictures/'.$article->id) }}
							</a></article>
							<br>
						@else
							<a href="/articles/{{ $article->slug }}"><button class="btn btn-primary">Read More</button></a>
						@endif
					</td>
				</tr>
				<tr><td><hr></td></tr>
			@endforeach
		@else
			<hr>
			<h3>There are no articles at the moment.</h3>
		@endif
	</table>
	<br>
	@if(!isset($query))
		<div align="center"> 
		{!! $articles->links() !!}
		</div>	
	@endif
@endsection

@section('footer')
	<script>
		$(document).ready(function(){
		  $('.bxslider').bxSlider({
			  auto: true,
			  autoControls: true,
			  captions: true,
			  randomStart: true,
			  autoHover: true,
			  useCSS: false
			});
		});
	</script>
	@include('searchfooter')
@endsection