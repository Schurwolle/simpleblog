@extends('layouts.app')

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
									<h1 class="articleTitle"><a class="black" href="/articles/{{ $article->slug }}/{{isset($query_link) ? $query_link : ''}}">{{$article->title}}</a></h1>
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
					<td class="articleBody" align="justify">
						<div>
						<?php $body = str_replace(array('<li>','</li>','<h2>','<h3>'),array('•','&nbsp','<h4>','<h4>'), strip_tags($article->body, '<a>,<h2>,<h3>,<h4>,<h5>,<strong><em><s><li><span>')); ?>
						@if(str_word_count($body) > 80)
							@if(count(preg_split("/(<|>)/", \Illuminate\Support\Str::words($body, 80), null, PREG_SPLIT_DELIM_CAPTURE)) > 2)
								@for($i = 80; preg_split("/(<|>)/", \Illuminate\Support\Str::words($body, $i), null, PREG_SPLIT_DELIM_CAPTURE)[count(preg_split("/(<|>)/", \Illuminate\Support\Str::words($body, $i), null, PREG_SPLIT_DELIM_CAPTURE))-2] == "<";$i++)
								@endfor
								{!! \Illuminate\Support\Str::words($body, $i) !!}
							@else
								{!! \Illuminate\Support\Str::words($body, 80) !!}
							@endif
						@else
							{!! $body !!}
						@endif
						</div>
					</td>
				</tr>
				<tr>
					<td>
						@if (file_exists('pictures/'.$article->id))
							<article><a href="/articles/{{ $article->slug }}/{{isset($query_link) ? $query_link : ''}}"> 
								{{ Html::image('pictures/'.$article->id) }}
							</a></article>
							<br>
						@else
							<a href="/articles/{{ $article->slug }}/{{isset($query_link) ? $query_link : ''}}"><button class="btn btn-primary">Read More</button></a>
						@endif
					</td>
				</tr>
				@if(isset($query))
					<tr>
						<td>
							@unless ($article->tags->isEmpty())
								<h5>Tags: 
									@foreach($article->tags as $tag)
										<a href="/tags/{{ $tag->name }}"><button class="btn btn-default btn-xs @if($tag->name == strtolower(implode($query_words))) marker @else @foreach($query_words as $word) {{ $tag->name == strtolower($word) ? 'marker' : '' }}@endforeach @endif"> {{ $tag->name }} </button></a>
									@endforeach
								</h5>
							@endunless
						</td>
					</tr>
					@unless	(!isset($comments[$article->id]))
						<tr><td><br></td></tr>
						@foreach($comments[$article->id] as $comment)
							<tr>
								<td align="middle">
									<div class="row searchComment">
										<div class="col-sm-2">
											<div class="thumbnail">
											<a href="/{{$comment->user->name}}/profile"><img src="{{ file_exists('pictures/'.$comment->user->name) ? '/pictures/'.$comment->user->name : '/img/avatar.png' }}"></a>
											</div>
										</div>
										<div class="col-sm-10">
											<div class="panel panel-default">
												<div class="panel-heading">
													<a class="black" href="/{{$comment->user->name}}/profile"><strong>{{$comment->user->name}}</strong></a>
													<span class="text-muted">
														commented {{$comment->created_at->diffForHumans()}}
														@if ($comment->updated_at > $comment->created_at)
															(last edited {{$comment->updated_at->diffForHumans()}})
														@endif 
													</span>
												</div>
												<div name="panelbody" class="panel-body commentBody">
													@for($i = 60; ends_with(\Illuminate\Support\Str::words($comment->body, $i),"span..."); $i++)
													@endfor
													@if(str_word_count(strip_tags($comment->body)) > $i)
														{{\Illuminate\Support\Str::words($comment->body, $i)}}
													@else
														{{ $comment->body }}
													@endif
												</div>
												<div class="panel-body">
													<a href="articles/{{$article->slug}}/{{$query_link
													}}#comment{{$comment->id}}">
														<button class="btn btn-primary">{{ str_word_count(strip_tags($comment->body)) > $i ? 'See Full Comment' : 'Go To Comment'}}</button>
													</a>
												</div>
									        </div>	
										</div>	
							    	</div>
								</td>
							</tr>
						@endforeach
					@endunless
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
	@if(isset($query_words))
		@include('removeMarker')
		@if(isset($comments))
			@include('trimComments')
			@include('icontains')
			<script type="text/javascript">
				$('.commentBody').each(function(){
					if($(this).prop('scrollHeight') > 300 && $(this).next('.panel-body').find('button').text() != 'See Full Comment')
					{
						$(this).next('.panel-body').find('button').text('See Full Comment');
					}
				});
			</script>
		@endif
	@endif
	@include('searchfooter')
@endsection