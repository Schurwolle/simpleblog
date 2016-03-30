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
	function updating(){
		var tagname = $('#btnvalue').attr('name');
		var tagcount = $('#btnvalue').attr('value');
		if(tagname) 
		{
			var btntxt = tagname.concat(tagcount);	
			$('#name').closest('td').next('td').children('.btn-default').unbind('click');
			$('#name').closest('td').next('td').children('.btn-default').bind('click', updating);
			$('#name').closest('td').next('td').children('.btn-default').html('<i class="fa fa-edit"></i> Edit');
			$('#name').closest('td').next('td').children('.btn-warning').remove();
			$('#name').closest('td').html('<a href="/tags/'+ tagname +'"><button class="btn btn-default">'+ btntxt +'</button></a>');
		}
		var txt = $(this).closest('td').prev('td').find('.btn-default').text();
		tagname = txt.substring(0, txt.length-3);
		tagcount = txt.substring(txt.length-4, txt.length);
		$(this).closest('td').prev('td').html('<form action="/tags/'+ tagname +'"method="POST" id = "updateform"><input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="text" id="name" name="name" class="form-control" value='+ tagname +'><span id = "btnvalue" style="visibility:hidden" name ="'+ tagname +'" value = "'+ tagcount +'"></span>');
		$(this).closest('td').prev('td').find('input').focus();
		$(this).unbind('click');
		$(this).on('click', function(){
			$('#updateform').submit();
		});
		$(this).html('<i class="fa fa-plus"></i> Update');
		$(this).closest('td').append('<button class="btn btn-warning" style="width: 85px;" type="button"><i class="fa fa-remove"></i> Cancel</button></form>')
		$('.btn-warning').on('click', function(){
			$(this).closest('td').prev('td').html('<a href="/tags/'+ tagname +'"><button class="btn btn-default">'+ txt +'</button></a>');
			$(this).siblings('.btn-default').unbind('click');
			$(this).siblings('.btn-default').bind('click', updating);
			$(this).siblings('.btn-default').html('<i class="fa fa-edit"></i> Edit');
			$(this).remove();
		});
	}
	$('td').children('.btn-default').on('click', updating);
</script>

@stop