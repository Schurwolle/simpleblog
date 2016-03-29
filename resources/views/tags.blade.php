@extends('layouts.app')

@section('content')
	<h1>Tags:</h1>
	<hr>
	<table class="table table-striped table-bordered">
	@if($tags->count() > 0)
		@foreach ($tags as $tag)
			<tr class="tag"><td>
					<a href="/tags/{{ $tag->name }}"><button class="btn btn-default">{{ $tag->name }} ({{ $tag->articles->count() }})</button></a>
				</td>
				<td>
					<button class="btn btn-default"><i class="fa fa-edit"></i> Edit</button>
				</td>
				<td>
					{!!Form::open(['method' => 'DELETE', 'url' => 'tags/'.$tag->name ])!!}

	      				{!!Form::button('<i class="fa fa-trash"></i> Delete', array('id' => 'delete', 'class' => 'btn btn-danger'))!!}

	    			{!!Form::close()!!}
				</td>
			</tr>
		@endforeach
	@else
		<h3>There are no tags at the moment.</h3>
	@endif
	</table>
@stop

@section('footer')

@include('ConfirmDelete')

<script type="text/javascript">

	$('td').children('.btn-default').on('click',function(){
		var tagname = $('.updating').find('input').val();
		$('.updating').removeClass('updating').addClass('tag').html('<td><a href="/tags/'+ tagname +'"><button class="btn btn-default">'+tagname +'</button></a></td><td><button class="btn btn-default"><i class="fa fa-edit"></i> Edit</button></td><td><form action="tags/'+ tagname +'" method="DELETE"><button id="delete" class="btn btn-danger"><i class="fa fa-trash"></i> Delete </button></form></td>');

		var txt = $(this).closest('.tag').find('.btn-default').html();
		var tagname = txt.substring(0, txt.length-3);
		$(this).closest('.tag').removeClass('tag').addClass('updating');
		$('.updating').html('<td><form action="/tags/'+ tagname +'"method="POST"><input type="text" class="form-control" value='+ tagname +' autofocus></td><td><button type="submit" class="btn btn-default"><i class="fa fa-plus"></i> Update</button><button class="btn btn-warning"><i class="fa fa-remove"></i> Cancel</button></form></td><td><form action="tags/'+ tagname +'" method="DELETE"><button id="delete" class="btn btn-danger"><i class="fa fa-trash"></i> Delete </button></form></td>');

		$('.btn-warning').on('click', function(){
		$('.updating').removeClass('updating').addClass('tag');
		$(this).closest('.tag').html('<td><a href="/tags/'+ tagname +'"><button class="btn btn-default">'+txt +'</button></a></td><td><button class="btn btn-default"><i class="fa fa-edit"></i> Edit</button></td><td><form action="tags/'+ tagname +'" method="DELETE"><button id="delete" class="btn btn-danger"><i class="fa fa-trash"></i> Delete </button></form></td>')
		});
		
	});

	$('.btn-warning').on('click', function(){
		$('.update').remove();	
	});
</script>

@stop