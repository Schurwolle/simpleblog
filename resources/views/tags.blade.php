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
			var td = $('#name').closest('td').next('td');
			td.children('.btn-default')
					.unbind('click')
					.bind('click', updating)
					.html('<i class="fa fa-edit"></i> Edit')
			;
			td.children('.btn-warning').remove();
			$('#name').closest('td').html('<a href="/tags/'+ tagname +'"><button class="btn btn-default">'+ btntxt +'</button></a>');
		}
		var txt = $(this).closest('td').prev('td').find('.btn-default').text();
		tagname = txt.substring(0, txt.length-4);
		tagcount = txt.substring(txt.length-4, txt.length);
		var td = $(this).closest('td').prev('td');
		td
			.html('<form action="/tags/'+ tagname +'"method="POST" id = "updateform" onkeypress="return event.keyCode != 13;"><input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="text" id="name" name="name" class="form-control" value='+ tagname +'><span id = "btnvalue" style="visibility:hidden" name ="'+ tagname +'" value = "'+ tagcount +'"></span>')
			.find('input').focus()
		;
		$('#name').bind('enterKey', ajaxUpdate);
		$('#name').keyup(function(e){
		    if(e.keyCode === 13)
		    {
		        $(this).trigger('enterKey');
		    }
		});
		$(this)
			.unbind('click')
			.bind('click', ajaxUpdate)
		;
		$(this).html('<i class="fa fa-plus"></i> Update');
		$(this).closest('td').append('<button class="btn btn-warning" style="width: 85px;" type="button"><i class="fa fa-remove"></i> Cancel</button></form>')
		$('.btn-warning').on('click', function(){
			td.html('<a href="/tags/'+ tagname +'"><button class="btn btn-default">'+ txt +'</button></a>');
			$(this).siblings('.btn-default')
					.unbind('click')
					.bind('click', updating)
					.html('<i class="fa fa-edit"></i> Edit')
			;
			$(this).remove();
		});
		function ajaxUpdate(){
				var newtagname = $('#name').val();
				if($.trim(newtagname).length === 0)
				{
					return swal({   title: "Error!",   text: "Tag name cannot be empty.", timer: 1500,   showConfirmButton: false, type:"error" });
				}
				if (tagname === newtagname)
				{
					return change(td, newtagname, txt);
				}
				txt = newtagname.concat(tagcount);
				dataString = $("#updateform").serialize();
				$.ajax({				 
					 url: "/tags/"+tagname,
					 type: "POST",
					 data: dataString,
					 success: [msg(), change(td, newtagname, txt)]
				});
			}
	}
	$('td').children('.btn-default').on('click', updating);

	function change(td, newtagname, txt) 
	{
		td.html('<a href="/tags/'+ newtagname +'"><button class="btn btn-default">'+ txt +'</button></a>');
	 	td.next('td').children('.btn-default')
	 			.unbind('click')
	 			.bind('click', updating)
	 			.html('<i class="fa fa-edit"></i> Edit')
	 	;
	 	td.next('td').children('.btn-warning').remove();
	}
	function msg()
	{
		swal({   title: "Success!",   text: "The tag has been updated!", timer: 1000,   showConfirmButton: false, type:"success" });
	}
</script>

@stop