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
	.row:after {
		clear: none;
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
						<td id ="counters" align="right">
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
			    	@if(Auth::user()->favorites->contains($article->id))
			    		<button id="fav" title="favorited" style="color: gold;" class="btn btn-success">
					     	<i class="fa fa-star"></i> Favorited!
					    </button>
			    	@else
					    <button  id="fav" title="favorite" class="btn btn-success">
					     	<i class="fa fa-star"></i>  Favorite
					    </button>
				    @endif
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
	    
	    <form method="post" action="/comment" id ="addform">
	        <input type="hidden" name="_token" value="{{ csrf_token() }}">
	        <input type="hidden" name="article_id" value="{{ $article->id }}">
	        <a name="comments" class="anchor"></a>
	        <div class="row">
		        <div class="col-sm-2">
					<div class="thumbnail">
						<a id="link" href="/{{Auth::user()->name}}/profile"><img src="{{ file_exists('pictures/'.Auth::user()->name) ? '/pictures/'.Auth::user()->name : '/img/avatar.png' }}"></a>
					</div>
				</div>
		        <div class="col-sm-10">
					<div class="panel panel-default">
						<div class="panel-heading">
							<a style="color:black;" href="/{{Auth::user()->name}}/profile"><strong>{{Auth::user()->name}}</strong></a>
						</div>
		          		<textarea required="required" placeholder="Your Comment" name = "body" class="form-control" rows="4"></textarea>
		          	</div>
		          	<button type="button" id="addcomment" name='article_comment' class="btn btn-primary"><i class="fa fa-plus"></i> Add Comment</button>
		          	<br><br>
		        </div>
	        </div>
	    </form>
	    @unless($comments->isEmpty())
		    <h3 id ="numComm">{{ $comments->count() }} {{ $comments->count() == 1 ? ' Comment:' : ' Comments:' }} </h3>
		    <hr>
		    @foreach($comments as $comment)
		    	<div class="row">
		    		<a name="{{$comment->id}}" class="anchor"></a>
					<div class="col-sm-2">
						<div class="thumbnail">
							<a href="/{{$comment->user->name}}/profile"><img src="{{ file_exists('pictures/'.$comment->user->name) ? '/pictures/'.$comment->user->name : '/img/avatar.png' }}"></a>
						</div>
					</div>

					<div class="col-sm-10">
						<div class="panel panel-default">
							<div class="panel-heading">
								<a style="color:black;" href="/{{$comment->user->name}}/profile"><strong id="username">{{$comment->user->name}}</strong></a>
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
							 	<div id="{{$comment->id}}" class="panel-body">
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
				            @endif
				        </div>	
					</div>	
		    	</div>
		    @endforeach
	    @endunless
  	@endif
@stop

@section('footer')

@include('ConfirmDelete')

<script type="text/javascript">
	$('button#addcomment').on('click', function(){
		var hr = $('#addform').next('h3').next('hr');
		var src = $('#addform').find('img').attr('src');
		var href = $('#link').attr('href');
		var username = ($('#username').text()).trim();
		var comment = $('#addform').find('textarea').val();
		var dataString = $('#addform').serialize();
		$.ajax({
			url: "/comment",
			type: "POST",
			data: dataString,
			success:function(){
				if(!hr.length)
				{
					$('#addform').after('<h3 id="numComm"></h3><hr>');
					hr = $('#addform').next('h3').next('hr');
				}
				hr.after('<div class="row"><div class="col-sm-2"><div class="thumbnail"><a href="'+ href +'"><img src='+ src +'></a></div></div><div class="col-sm-10"><div class="panel panel-default"><div class="panel-heading"><a style="color:black;" href="'+ href +'"><strong>'+ username +'</strong></a><span class="text-muted"> commented 1 second ago</span></div><div class="panel-body">'+ comment +'</div><div id="" class="panel-body"><table style=""><tr><td><button id="edit" class="btn btn-primary"><i class="fa fa-edit"></i> Edit</button></td><td><form method="DELETE"><input type="hidden" name="_token" value="{{ csrf_token() }}"><button class="btn btn-danger" id="delete" type="button"><i class="fa fa-trash"></i> Delete</button></form></td></tr></table></div></div></div></div>');
				$('button#edit').on('click', updating);
				$('button#delete').on('click', confirmDelete);
				$('#addform').find('textarea').val('');
				var counters = $('#counters').text();
		        var numComm = counters.trim();
		        numComm = parseInt(numComm.substring(numComm.length-2, numComm.length)) + 1;
		        var numFavs = {{ $article->favoritedBy->count() }}
		        $('#counters').html('<i class="fa fa-star" style="color: gold;"></i> '+ numFavs +'  &nbsp <i class="fa fa-comment-o" style="color: purple;"></i> '+numComm)
		        $('#numComm').text(numComm == 1 ? numComm + ' Comment:' : numComm + ' Comments:')
			}
		});
	});

	function updating (){
		var txt = $('#area').attr('value');
		var id = $('#area').attr('name');
		if(txt)
		{
			$('#area').closest('.panel-body').next('.panel-body').find('.btn-primary').unbind('click');
			$('#area').closest('.panel-body').next('.panel-body').find('.btn-primary').bind('click', updating);
			$('#area').closest('.panel-body').next('.panel-body').find('.btn-primary').html('<i class="fa fa-edit"></i> Edit');
			$('#area').closest('.panel-body').next('.panel-body').find('.btn-warning').remove();
			$('#area').closest('.panel-body').next('.panel-body').find('.btn-danger').show();
			$('#area').closest('.panel-body').html(txt);
		}
		txt = $(this).closest('.panel-body').prev('.panel-body').text();
		txt = txt.trim();
		id  = $(this).closest('.panel-body').attr('id');
		$(this).closest('.panel-body').prev('.panel-body').html('<form method="POST" action="/comment/'+ id +'"id = "updateform"><input type="hidden" name="_token" value="{{ csrf_token() }}"><textarea id="body" class="form-control" required="required" rows="6" name="body">'+ txt +'</textarea><span id="area" style="visibility:hidden" name ="'+ id +'" value= "'+ txt +'"></span>');
		$(this).closest('.panel-body').prev('.panel-body').find('textarea').focus();
		$(this).unbind('click');	
		$(this).bind('click', function(){
			var panel = $(this).closest('.panel-body').prev('.panel-body');
			var newtxt = panel.find('textarea').val();
			created = panel.siblings('.panel-heading').children('span').text()
			created = created.trim();
			created = created.substring(0, 22);
			dataString = $("#updateform").serialize();
			$.ajax({				 
				 url: "/comment/"+id,
				 type: "POST",
				 data: dataString,
				 success: function(){
				 	panel.html(newtxt);
				 	panel.next('.panel-body').find('.btn-primary').unbind('click');
				 	panel.next('.panel-body').find('.btn-primary').bind('click', updating);
				 	panel.next('.panel-body').find('.btn-primary').html('<i class="fa fa-edit"></i> Edit');
				 	panel.next('.panel-body').find('.btn-danger').show();
				 	panel.next('.panel-body').find('.btn-warning').remove();
				 	if(txt != newtxt) 
				 	{
				 		panel.siblings('.panel-heading').children('span').html(created + '(last edited 1 second ago)');
				 	}
				 }
			});
		});
		$(this).html('<i class="fa fa-plus"></i> Update');
		$(this).closest('.panel-body').find('.btn-danger').hide();
		$(this).closest('td').next('td').append('<button class="btn btn-warning" style="width: 85px;" type="button"><i class="fa fa-remove"></i> Cancel</button></form>');
		$('.btn-warning').on('click', function(){
			$(this).closest('.panel-body').prev('.panel-body').html(txt);
			$(this).closest('.panel-body').find('.btn-primary').unbind('click');
			$(this).closest('.panel-body').find('.btn-primary').bind('click', updating);
			$(this).closest('.panel-body').find('.btn-primary').html('<i class="fa fa-edit"></i> Edit');
			$(this).closest('.panel-body').find('.btn-danger').show();
			$(this).remove();
		});
	}

	$('.panel-body').find('.btn-primary').on('click', updating);


	$('button#fav').on('click', function() {
	    $.ajax({
	      url: "{{$article->slug}}/favorite",
	      success: function(){
	      	var counters = $('#counters').text();
	        var numFavs = parseInt(counters.trim().substring(0,1));
	        var numComm = {{ $article->comments->count() }}
	      	if ($('button#fav').attr('title') === 'favorite')
	      	{
	           $('button#fav')
	           		 .attr('title', 'favorited')
	           		 .css('color', 'gold')
	                 .html('<i class="fa fa-star"></i> Favorited!')
	           ;
	           numFavs += 1;
	           $('#counters').html('<i class="fa fa-star" style="color: gold;"></i> '+ numFavs +'  &nbsp <i class="fa fa-comment-o" style="color: purple;"></i> '+numComm)
	        } else {
	        	$('button#fav')
	        		 .attr('title', 'favorite')
	           		 .css('color', 'white')
	                 .html('<i class="fa fa-star"></i> Favorite')     
	           ;
	           numFavs -= 1;
	           $('#counters').html('<i class="fa fa-star" style="color: gold;"></i> '+ numFavs +'  &nbsp <i class="fa fa-comment-o" style="color: purple;"></i> '+numComm)
	        }
	      }
	    });
	});
</script>

@stop