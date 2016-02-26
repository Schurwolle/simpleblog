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
	<br>
	<table><tr>
	<td>
	<button class="btn btn-primary" onclick="history.go(-1)">
      « Back
    </button>
    </td>
    @if($article->user_id == Auth::id() || Auth::user()->isAdmin())
    <td>
    <a href="{{ $article->id }}/edit"><button class="btn btn-primary">
     	Edit
    </button></a>
    </td>
    <td>
    {!!Form::open(['method' => 'DELETE', 'url' =>'/articles/'.$article->id.'/delete', 'onsubmit' => 'return ConfirmDelete()' ])!!}

		{!!Form::button('<i class="fa fa-btn fa-trash"></i>Delete', array('type' => 'submit', 'class' => 'btn btn-danger'))!!}

	{!!Form::close()!!}
	</td>
    @endif
    </tr>
    </table>
    <hr>
    @if($article->published_at <= Carbon\Carbon::now())
	    <article>Article published by <a href="/{{ $article->user->name }}/profile">{{ $article->user->name }}</a> on {{ $article->published_at }}.</article>
    @else
    	<article>Article set to be published on {{ $article->published_at }} by <a href="/{{ $article->user->name }}/profile">{{ $article->user->name }}</a>.</article>
    @endif

    <h3>Leave a Comment:</h3>
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
	              @if($comment->user_id == Auth::id() || Auth::user()->isAdmin())
	              <p>
	              {!!Form::open(['method' => 'DELETE', 'url' => '/comment/delete/'.$comment->id, 'onsubmit' => 'return ConfirmDelete()' ])!!}

	              	{!!Form::button('<i class="fa fa-btn fa-trash"></i>Delete', array('type' => 'submit', 'class' => 'btn btn-danger'))!!}
	              	
	              {!!Form::close()!!}
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

@section('footer')
<script>

  function ConfirmDelete()
  {
  var x = confirm("Are you sure you want to delete?");
  if (x)
    return true;
  else
    return false;
  }

</script>
@stop