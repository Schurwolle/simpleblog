@extends('layouts.app')

@section('content')

	<h1>{{ $article->title }} </h1>
	<hr>

	<article>
		{!! html_entity_decode($article->body) !!}
	</article>
	<hr>
	@if (file_exists('pictures/'.$article->id))
	{{ Html::image('pictures/'.$article->id) }}
	<br>
	@endif
	
	@unless ($article->tags->isEmpty())
		<h5>Tags:
		
			@foreach($article->tags as $tag)
				<a href="/tags/{{ $tag->name }}"> {{ $tag->name }} </a>
			@endforeach
		</h5>
	@endunless

	<button class="btn btn-primary" onclick="history.go(-1)">
      « Back
    </button>
    @if($article->user_id == Auth::id())
    <a href="{{ $article->id }}/edit"><button class="btn btn-primary">
     	Edit
    </button></a>
    <a href="{{ $article->id }}/delete"><button class="btn btn-danger">
     	Delete 
    </button></a>
    @endif
    <hr>
    <article>Article published by <a href="/{{ $article->user->name }}/profile">{{ $article->user->name }}</a>
    		 on {{ $article->published_at }}.
    </article>


    <h3>Leave a Comment</h3>
    <div class="panel-body">
      <form method="post" action="/comment/add">
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
	              <h3>{{ $comment->user->name }}</h3>
	              <p>{{ $comment->created_at->diffForHumans() }}</p>
	            </div>
	            <div class="list-group-item">
	              <p>{{ $comment->body }}</p>
	              @if($comment->user_id == Auth::id())
	              <p>
	              	<a href="/comment/delete/{{$comment->id}}">
	              	<button class="btn btn-danger">
	              	Delete</button></a>
	              </p>
	              @endif
	            </div>
	          </div>
	        </li>
	      @endforeach
	    </ul>
	    @endunless
  </div>
@stop