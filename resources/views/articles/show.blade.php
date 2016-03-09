@extends('layouts.app')

@section('head')
<style>
	.thumbnail {
	    padding:0px;
	}
	.panel {
		position:relative;
	}
	.panel>.panel-heading:after,.panel>.panel-heading:before{
		position:absolute;
		top:11px;left:-16px;
		right:100%;
		width:0;
		height:0;
		display:block;
		content:" ";
		border-color:transparent;
		border-style:solid solid outset;
	}
	.panel>.panel-heading:after{
		border-width:7px;
		border-right-color:#f7f7f7;
		margin-top:1px;
		margin-left:2px;
	}
	.panel>.panel-heading:before{
		border-right-color:#ddd;
		border-width:8px;
	}
</style>

@endsection

@section('sides')
	@include('leftandright')
@endsection

@section('content')

	<h1>{{ $article->title }} </h1>
	<hr>

	<article>
		{!! html_entity_decode($article->body) !!}
	</article>
	<hr>
	@if (file_exists('pictures/'.$article->id))
		<article>
			{{ Html::image(('pictures/'.$article->id), null, ['style' => 'max-width: 650px; height: auto;']) }}
		</article>
	<br>
	@endif
	
	@unless ($article->tags->isEmpty())
		<h5>Tags: 
			@foreach($article->tags as $tag)
				<a href="/tags/{{ $tag->name }}"><button class="btn btn-default btn-xs"> {{ $tag->name }} </button></a>
			@endforeach
		</h5>
	@endunless
	<br>
	<table><tr>
	<td>
	<button class="btn btn-primary" onclick="history.go(-1)">
      « Back
    </button>
    </td>
    @if($article->user_id == Auth::id() || Auth::user()->isAdmin())
    <td>
    <a href="{{ $article->slug }}/edit"><button class="btn btn-primary">
     	Edit
    </button></a>
    </td>
    <td>
    {!!Form::open(['method' => 'DELETE', 'url' =>'/articles/'.$article->slug, 'onsubmit' => 'return ConfirmDelete()' ])!!}

		{!!Form::button('<i class="fa fa-btn fa-trash"></i>Delete', array('type' => 'submit', 'class' => 'btn btn-danger'))!!}

	{!!Form::close()!!}
	</td>
    @endif
    </tr>
    </table>
    <hr>
    @if($article->published_at > Carbon\Carbon::now())
	    <article>Article set to be published on {{ $article->published_at }} by <a href="/{{ $article->user->name }}/profile">{{ $article->user->name }}</a>.</article>
    @else
    	<article>Article published by <a href="/{{ $article->user->name }}/profile">{{ $article->user->name }}</a> on {{ $article->published_at }}.</article>
    

	    <h3>Leave a Comment:</h3>
	    <div class="panel-body">
	      <form method="post" action="/comment">
	        <input type="hidden" name="_token" value="{{ csrf_token() }}">
	        <input type="hidden" name="article_id" value="{{ $article->id }}">
	        <div class="form-group">
	          <textarea required="required" placeholder="Your Comment" name = "body" class="form-control"></textarea>
	        </div>
	        <input type="submit" name='article_comment' class="btn btn-primary" value = "Post"/>
	      </form>
	    </div>
	    <div class="center">
		    @unless($comments->isEmpty())
			    <h3>Comments: </h3>
			    <hr>
			    @foreach($comments as $comment)
					<div class="row">
						<div class="col-sm-2">
							<div class="thumbnail">
								<a href="/{{$comment->user->name}}/profile"><img src="{{ file_exists('pictures/'.$comment->user->name) ? '/pictures/'.$comment->user->name : 'https://ssl.gstatic.com/accounts/ui/avatar_2x.png' }}"></a>
							</div>
						</div>

						<div class="col-sm-10">
							<div class="panel panel-default">
								<div class="panel-heading">
									<a style="color:black;" href="/{{$comment->user->name}}/profile"><strong>{{$comment->user->name}}</strong></a>
									<span class="text-muted">
										commented {{$comment->created_at->diffForHumans()}}
										@if ($comment->updated_at > $comment->created_at)
											(last edited {{$comment->updated_at->diffForHumans()}})
										@endif 
									</span>
								</div>
								<div class="panel-body">
									{{$comment->body}}
								</div>
								@if($comment->user_id == Auth::id() || Auth::user()->isAdmin())
								 	<div class="panel-body">
						              <table style=""><tr><td>
						              	<a href="/comment/{{ $comment->id }}/edit"><button class="btn btn-primary">
			     									Edit
			    						</button></a>
			    					  </td>
						              <td>
						              {!!Form::open(['method' => 'DELETE', 'url' => '/comment/'.$comment->id, 'onsubmit' => 'return ConfirmDelete()' ])!!}

						              	{!!Form::button('<i class="fa fa-btn fa-trash"></i>Delete', array('type' => 'submit', 'class' => 'btn btn-danger'))!!}
						              	
						              {!!Form::close()!!}
						              </td>
						              </tr>
						              </table>
						            </div>
					            @endif
					        </div>	
						</div>
					</div>
			    @endforeach
		    @endunless
		    <hr>
	  	</div>
  	@endif
@stop

@section('footer')

@include('ConfirmDelete')

@stop