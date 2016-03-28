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
	.col-sm-2 {
		float: left;
		width: 16.66666667%;
	}
	.col-sm-10 {
		float: left;
		width: 83.33333333%;
	}

</style>

@endsection

@section('sides')
	@include('leftandright')
@endsection

@section('content')
	<table>
		<tr>
			<td>
				<table width="100%">
					<tr valign="baseline">
						<td>
							<h1>{{ $article->title }} </h1>
						</td>
						<td align="right">
							<i class="fa fa-star" style="color: gold;"></i> {{ $article->favoritedBy->count() }}
							&nbsp
							<i class="fa fa-comment-o" style="color: purple;"></i> {{ $article->comments->count() }}
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr><td><hr></td></tr>
		<tr>
			<td align="justify">
				{!! html_entity_decode($article->body) !!}
			</td>
		<tr><td><hr></td></tr>
		@if (file_exists('pictures/'.$article->id))
			<tr>
				<td>
					<a name="favorite" class="anchor"></a> 
					{{ Html::image(('pictures/'.$article->id)) }}
				</td>
			</tr>
		@endif
	</table>
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
	    <td>
	    	@if(App\article::published()->get()->contains($article))
	    		<a href="{{ $article->slug }}/favorite">
			    	@if(Auth::user()->favorites->contains($article->id))
			    		<button style="color: gold;" class="btn btn-success">
					     	<i class="fa fa-star"></i> Favorited!
					    </button></a>
			    	@else
					    <button class="btn btn-success">
					     	<i class="fa fa-star"></i>  Favorite
					    </button>
				    @endif
			    </a>
			@endif
	    </td>
	    @if($article->user_id == Auth::id() || Auth::user()->isAdmin())
		    <td>
			    <a href="{{ $article->slug }}/edit"><button class="btn btn-primary">
			     	<i class="fa fa-edit"></i> Edit
			    </button></a>
		    </td>
		    <td>
			    {!!Form::open(['method' => 'DELETE', 'url' =>'/articles/'.$article->slug])!!}

					{!!Form::button('<i class="fa fa-trash"></i> Delete', array('id' => 'delete', 'class' => 'btn btn-danger'))!!}

				{!!Form::close()!!}
			</td>
	    @endif
	</tr></table>

    <hr>

    @if(!App\article::published()->get()->contains($article))
	    <article>Article set to be published on {{ $article->published_at }} by <a href="/{{ $article->user->name }}/profile">{{ $article->user->name }}</a>.</article>
    @else
    	<article>Article published by <a href="/{{ $article->user->name }}/profile">{{ $article->user->name }}</a> on {{ $article->published_at }}.</article>
    

	    <h3>Leave a Comment:</h3>
	    
	    <form method="post" action="/comment">
	        <input type="hidden" name="_token" value="{{ csrf_token() }}">
	        <input type="hidden" name="article_id" value="{{ $article->id }}">
	        <a name="comments" class="anchor"></a>
	        <div class="col-sm-2">
				<div class="thumbnail">
					<a href="/{{Auth::user()->name}}/profile"><img src="{{ file_exists('pictures/'.Auth::user()->name) ? '/pictures/'.Auth::user()->name : '/img/avatar.png' }}"></a>
				</div>
			</div>
	        <div class="col-sm-10">
				<div class="panel panel-default">
					<div class="panel-heading">
						<a style="color:black;" href="/{{Auth::user()->name}}/profile"><strong>{{Auth::user()->name}}</strong></a>
					</div>
	          		<textarea required="required" placeholder="Your Comment" name = "body" class="form-control" rows="4"></textarea>
	          	</div>
	          	<button type="submit" name='article_comment' class="btn btn-primary"><i class="fa fa-plus"></i> Add Comment</button>
	        </div>
	    </form>
	    @unless($comments->isEmpty())
		    <h3>Comments: </h3>
		    <hr>
		    <div class="row">
		    	@foreach($comments as $comment)
		    		<a name="{{$comment->id}}" class="anchor"></a>
					<div class="col-sm-2">
						<div class="thumbnail">
							<a href="/{{$comment->user->name}}/profile"><img src="{{ file_exists('pictures/'.$comment->user->name) ? '/pictures/'.$comment->user->name : '/img/avatar.png' }}"></a>
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
					              	<button class="btn btn-primary">
		     									<i class="fa fa-edit"></i> Edit
		    						</button>
		    					  </td>
					              <td>
					              {!!Form::open(['method' => 'DELETE', 'url' => '/comment/'.$comment->id ])!!}

					              	{!!Form::button('<i class="fa fa-trash"></i> Delete', array('class' => 'btn btn-danger', 'id' => 'delete'))!!}
					              	
					              {!!Form::close()!!}
					              </td>
					              </tr>
					              </table>
					            </div>
        						<div class="update">
							        <div class="panel-body">
										{!! Form::model($comment, ['method' => 'PATCH', 'url' => '/comment/'.$comment->id ] ) !!}
									        <div class="form-group">
									        {!! Form::textarea('body', null, ['id' =>'body' , 'class' => 'form-control', 'required', 'rows' => '6']) !!}
									        </div>
									        {!!Form::button('<i class="fa fa-plus"></i> Update', ['class' => 'btn btn-primary', 'type' => 'submit'])!!}
									        {!!Form::button('<i class="fa fa-remove"></i> Cancel', ['class' => 'btn btn-warning'])!!}
									      {!!Form::close()!!}
									</div>
								</div>
				            @endif
				        </div>	
					</div>
		    	@endforeach
		    </div>
	    @endunless
	    <hr>
  	@endif
@stop

@section('footer')

@include('ConfirmDelete')

<script type="text/javascript">
	$('.update').hide();
	$('td').children('.btn-primary').on('click', function(){
		$('.panel-body').show();
		$('.update').hide();
		$(this).closest('div').hide();
		$(this).closest('div').prev('div').hide();
		$(this).closest('div').next('div').show();
	});

	$('.btn-warning').on('click', function(){
		$('.panel-body').show();
		$('.update').hide();
	});
</script>

@stop