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
					<button class="btn btn-danger" id ="deleteTag" data-token="{{ csrf_token() }}"><i class="fa fa-trash"></i> Delete</button>
				</td>
			</tr>
		@endforeach
	@else
		<h3>There are no tags at the moment.</h3>
	@endif
	</table>
@stop

@section('footer')


<script type="text/javascript">
	function updating(){
		var tagname = $('#btnvalue').attr('name');
		if(tagname != null) 
		{	
			var tagcount = $('#btnvalue').attr('value');
			var txt = tagname.concat(tagcount);
			var td = $('#name').closest('td');
			change(td, tagname, txt);
		}
		txt = $(this).closest('td').prev('td').find('.btn-default').text();
		tagname = txt.substring(0, txt.length-4);
		tagcount = txt.substring(txt.length-4, txt.length);
		td = $(this).closest('td').prev('td');
		td
			.html('<form action="/tags/'+ tagname +'"method="POST" id = "updateform" onkeypress="return event.keyCode != 13;"><input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="text" id="name" name="name" class="form-control"><span id = "btnvalue" style="visibility:hidden" name ="'+ tagname +'" value = "'+ tagcount +'"></span>')
			.find('#name').focus().val(tagname)
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
			change(td, tagname, txt);
		});
		function ajaxUpdate(){
			var newtagname = $('#name').val();
			if(validateName(newtagname) === false)
			{
				return swal({   title: "Error!",   text: "Tag name cannot be empty and can contain only alphanumeric characters.", timer: 2000,   showConfirmButton: false, type:"error" });
			}
			if (tagname === newtagname)
			{
				return change(td, tagname, txt);
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

	function change(td, tagname, txt) 
	{
		td.html('<a href="/tags/'+ tagname +'"><button class="btn btn-default">'+ txt +'</button></a>');
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
	function validateName(newtagname)
	{
		if($.trim(newtagname).length === 0)
		{
			return false;
		}
	    if(/[^a-zA-Z0-9]/.test(newtagname)) 
	    {
	       return false;
	    }
	    return true;     
	 }

 	function confirmDeleteTag()
	{	
		var tagname = $(this).closest('tr').find('.btn-default').first().text();
		tagname = tagname.substring(0, tagname.length-4);
		var token = $(this).data('token');
		tag = $(this).closest('tr');
		swal({
        title: "Are you sure?",
        text: "Deleted files cannot be recovered!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, delete it!",
        closeOnConfirm: true
        }, function(isConfirm){
            if (isConfirm)
            {	
            	$.ajax({
            		url: '/tags/'+tagname,
            		type: 'post',	
            		data: {_method: 'delete', _token :token},
            		success: function(){
            			swal({   title: "Success!",   text: "The tag has been deleted!", timer: 1100,   showConfirmButton: false, type:"success" });
            			tag.remove();
            		}
            	});
            }
        });
	}
	$('button#deleteTag').on('click', confirmDeleteTag);
</script>

@stop