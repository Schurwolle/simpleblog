@extends('layouts.app')

@section('content')
	<table class="table table-striped table-bordered">
	<thead style="border-left: hidden;border-top: hidden;border-right: hidden;">
		<td style="vertical-align: baseline;">
			<h1>Tags: </h1>
		</td>
		<td style="border-left: hidden;border-right: hidden;">
		</td>
		<td align="right" style="vertical-align: baseline;">
			<button id="newTag" class="btn btn-default"><i class="fa fa-plus"></i> New Tag</button>
		</td>
	</thead>
	@if($tags->count() > 0)
		@foreach ($tags as $tag)
			<tr><td>
					<a href="/tags/{{ $tag->name }}"><button class="btn btn-default">{{ $tag->name }} ({{ $tag->articles->count() }})</button></a>
				</td>
				<td align="middle">
					<button class="btn btn-default"><i class="fa fa-edit"></i> Edit</button>
				</td>
				<td align="right">
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
	function newTag() {
		closeUpdateForm();
		$(this).unbind('click');
		$(this).bind('click', function(){
			$('#name').focus();
		});
		if($('#addTagRow').length)
		{
			$('#addTagRow').fadeIn();
		} else {
			$(this).closest('thead').next('tbody').prepend('<tr id="addTagRow" style="display:none"><td><form id="addform" method="POST" action ="/tags" onkeypress="return event.keyCode != 13;"><input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="text" name="name" id ="name" required="required" class="form-control"></form></td><td align="middle"><button id="add" class="btn btn-default"><i class="fa fa-plus"></i> Add</button></td><td align="right"><button id="cancel" class="btn btn-warning"><i class="fa fa-remove"></i> Cancel</button></td></tr></form>');
			$('#addTagRow').fadeIn();
			$('#name').bind('enterKey', ajaxAdd);
			ajaxOnEnter($('#name'));

			$('button#add').on('click', ajaxAdd);
			$('button#cancel').on('click', closeAddForm);
		}
		$('#name').focus();

		function ajaxAdd() {
			$(this).blur();
			var tagname = $('#name').val().trim();
			if(validateName(tagname) === false)
			{
				$('#name').focus();
				return;
			}
			$('button#newTag')
				.unbind('click')
				.bind('click', newTag)
			;
			var dataString = $('#addform').serialize();
			var tr = $(this).closest('tr');
			var tbody = $(this).closest('tbody');
			$.ajax({
				url: '/tags',
				type: 'POST',
				data: dataString,
				error: function(jqXHR) {
				  var err = jqXHR.responseText.substring(10,jqXHR.responseText.length-3);
				  errorMsg(err);
				},
				success: function(tag) {
					tr.hide();
					tr.find('#name').val('');
					tr.after('<tr style="display:none"><td><button class="btn btn-default">'+ tag.name +' (0)</button></td><td align="middle"><button id="editTag" class="btn btn-default"><i class="fa fa-edit"></i> Edit</button></td><td align="right"><button class="btn btn-danger" id ="deleteTag" data-token="{{ csrf_token() }}"><i class="fa fa-trash"></i> Delete</button></td></tr>');
					tr.next('tr').fadeIn();
					$('button#editTag').on('click', updating);
					$('button#deleteTag').on('click', confirmDeleteTag);
				}
			});
		}
	}
	$('button#newTag').on('click', newTag);

	function updating(){
		closeAddForm();
		closeUpdateForm();
		
		txt = $(this).closest('td').prev('td').find('.btn-default').text();
		tagname = txt.substring(0, txt.length-4);
		tagcount = txt.substring(txt.length-4, txt.length);
		td = $(this).closest('td').prev('td');
		td
			
			.html('<form action="/tags/'+ tagname +'"method="POST" id = "updateform" onkeypress="return event.keyCode != 13;"><input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="text" id="editName" name="name" class="form-control">')
			.find('#editName').focus().val(tagname)
		;
		td.parents('tr').css('display', 'none').fadeIn();
		$('#editName').bind('enterKey', ajaxUpdate);
		ajaxOnEnter($('#editName'));

		$(this)
			.unbind('click')
			.bind('click', ajaxUpdate)
		;
		$(this).html('<i class="fa fa-plus"></i> Update');
		if($(this).closest('td').children('.btn-warning').length)
		{
			$(this).closest('td').children('.btn-warning').show();
		} else {
			$(this).closest('td').append('<button class="btn btn-warning" style="width: 85px;" type="button"><i class="fa fa-remove"></i> Cancel</button></form>')
			$('.btn-warning').on('click', function(){
				change(td, tagname, txt);
			});
		}
		function ajaxUpdate(){
			$(this).blur();
			var newtagname = $('#editName').val().trim();
			if (tagname === newtagname)
			{
				return change(td, tagname, txt);
			}
			if(validateName(newtagname, tagname) === false)
			{
				$('#editName').focus();
				return; 
			}
			dataString = $("#updateform").serialize();
			$.ajax({				 
				 url: "/tags/"+tagname,
				 type: "POST",
				 data: dataString,
				 error: function(jqXHR) {
					  var err = jqXHR.responseText.substring(10,jqXHR.responseText.length-3);
					  errorMsg(err);
					},
				 success: function(name) {
				 	txt = name.concat(tagcount);
				 	change(td, name, txt);
				 }
			});
		}
	}
	function change(td, tagname, txt) 
	{
		td.html('<a href="/tags/'+ tagname +'"><button class="btn btn-default">'+ txt +'</button></a>');
	 	td.next('td').children('.btn-default')
	 			.unbind('click')
	 			.bind('click', updating)
	 			.html('<i class="fa fa-edit"></i> Edit')
	 	;
	 	td.next('td').children('.btn-warning').hide();
	 	td.parents('tr').css('display', 'none').fadeIn();
	}
	function errorMsg(err)
	{
		swal({ title: "Error!", text: err, timer: 2000, showConfirmButton: false, type:"error" });
	}
	function validateName(tagname, old_tagname)
	{
		if(tagname.length === 0)
		{	
			errorMsg("Tag name cannot be empty.");
			if(old_tagname)
			{
				$('#editName').val(old_tagname);
			}
			return false;
		}
		if(tagname.length > 32)
		{
			errorMsg("Tag name cannot be longer than 32 characters.");
			return false;
		}
	    if(/[^a-zA-Z0-9]/.test(tagname)) 
	    {
	    	errorMsg("Tag name can contain only alphanumeric characters.");
	        return false;
	    }
	}
	function closeAddForm()
	{
		if($('#addTagRow').length)
		{
			$('#addTagRow').fadeOut();
			$('button#newTag')
				.unbind('click')
				.bind('click', newTag)
			;
		}
	}
	function closeUpdateForm()
	{
		if($('input#editName').length)
		{
			change(td, tagname, txt);
		}
	}
	function ajaxOnEnter(name)
	{
		name.keyup(function(e){
		    if(e.keyCode === 13)
		    {
		        $(this).trigger('enterKey');
		    }
		});
	}
	$('tbody').find('td').children('.btn-default').on('click', updating);

 	function confirmDeleteTag()
	{	
		closeAddForm();
		closeUpdateForm();

		var btnDelete = $(this);
		var tag = btnDelete.closest('tr');
		var tagname = tag.find('.btn-default').first().text();
		tagname = tagname.substring(0, tagname.length-4);
		var token = btnDelete.data('token');

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
            			tag.fadeOut();
            			setTimeout(function() {
						  	tag.remove();
						}, 400);
            		}
            	});
            } else {
            	setTimeout(function() {
				  	btnDelete.blur();
				}, 0);
            }
        });
	}
	$('button#deleteTag').on('click', confirmDeleteTag);
</script>

@stop