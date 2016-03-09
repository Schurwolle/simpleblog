@extends('layouts.app')

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
	    <div>
		    @unless($comments->isEmpty())
			    <h3>Comments: </h3>
			    <ul style="list-style: none; padding: 0">
			      @foreach($comments as $comment)
			        <li class="panel-body">
			          <div class="list-group">
			            <div class="list-group-item">
			              	@if (file_exists('pictures/'.$comment->user->name))
			              		<a href="/{{$comment->user->name}}/profile">
			              		{{ Html::image(('pictures/'.$comment->user->name)) }}
			              		</a>
			              	@endif
			              <h3><a href="/{{$comment->user->name}}/profile">{{ $comment->user->name }}</a></h3>
			              <p>{{ $comment->created_at->diffForHumans() }}</p>
			            </div>
			            <div class="list-group-item">
			              <p>{{ $comment->body }}</p>
			              @if($comment->user_id == Auth::id() || Auth::user()->isAdmin())
			              <table><tr><td>
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
			              @endif
			              @if ($comment->updated_at > $comment->created_at)
			              	<p>Comment last edited {{ $comment->updated_at->diffForHumans() }}.</p>
			              @endif
			            </div>
			          </div>
			        </li>
			      @endforeach
			    </ul>
		    @endunless
	  	</div>
  	@endif
@stop

@section('footer')

@include('ConfirmDelete')

@stop