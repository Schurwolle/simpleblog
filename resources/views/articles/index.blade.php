@extends('layouts.app')

@section('head')

@endsection

@section('sides')
	@include('leftandright')
@endsection

@section('content')
    <div>
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
	<h1 class="sectionHeading">@yield('h1')</h1>
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
									<h1><a class="black" href="/articles/{{ $article->slug }}">{{ $article->title }}</a></h1>
								</td>
								<td align="right">
									<i class="fa fa-star{{ !$article->favoritedBy->contains(Auth::id()) ? '-o' : '' }} gold"></i> {{ $article->favoritedBy->count() }} 
									&nbsp
									<i class="fa fa-comment{{ !$article->hasCommentFromUser(Auth::id()) ? '-o' : '' }} purple"></i> {{ $article->comments->count() }}
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td id="articleBody" align="justify">{!! \Illuminate\Support\Str::words(str_replace(array('<li>','</li>'),array('•','&nbsp'), strip_tags($article->body, '<a>,<h2>,<h3>,<h4>,<h5>,<strong><em><s><li><span>')), 80) !!}</td>
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
				@if(isset($query))
					<tr>
						<td>
							@unless ($article->tags->isEmpty())
								<h5>Tags: 
									@foreach($article->tags as $tag)
										<a href="/tags/{{ $tag->name }}"><button class="btn btn-default btn-xs {{$tag->name == $query ? 'marker' :'' }}"> {{ $tag->name }} </button></a>
									@endforeach
								</h5>
							@endunless
						</td>
					</tr>
				@endif
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
	@if(isset($query))
		<script type="text/javascript">
			$('h1').find("a:contains(<span style='background-color:#FFFF00'>)").each(function(){
				$(this).html($(this).html().replace(/(&lt;span style='background-color:#FFFF00'&gt;)({{$query}})(&lt;\/span&gt;)/ig, "<span style='background-color:#FFFF00'>$2</span>"));
			});
			$('#articleBody').find('span.marker').removeClass('marker');
		</script>
	@endif
	@include('searchfooter')
@endsection