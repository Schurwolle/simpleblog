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
			<tr class="update"><td>
						{!!Form::open(['url' => 'tags/'.$tag->name])!!}
						
							{!!Form::text('name', $tag->name,['class' => 'form-control', 'id' => 'name'])!!}
							
					</td>
					<td>
							{!!Form::button('<i class="fa fa-plus"></i> Update',['class' => 'btn btn-default', 'type' => 'submit'])!!}
							{!!Form::button('<i class="fa fa-remove"></i> Cancel',['class' => 'btn btn-warning'])!!}

						{!!Form::close()!!}
					</td>
					<td>
					{!!Form::open(['method' => 'DELETE', 'url' => 'tags/'.$tag->name ])!!}

	      				{!!Form::button('<i class="fa fa-btn fa-trash"></i>Delete', array('id' => 'delete', 'class' => 'btn btn-danger'))!!}

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
	$('.update').hide();
	$('td').children('.btn-default').on('click',function(){
		$('.update').hide();
		$('.tag').show();
		$(this).closest('.tag').hide();
		$(this).closest('tr').next('tr').show();
		$(this).closest('tr').next('tr').find('input').focus();
	});

	$('.btn-warning').on('click', function(){
		$(this).closest('.update').hide();
		$(this).closest('tr').prev('tr').show();
	});
</script>

@stop