@extends('layouts.app')

@section('content')
	<h1>Tags:</h1>
	<hr>
	<table class="table table-striped table-bordered">
		@foreach ($tags as $thetag)
			@if ($thetag->name == $tag->name)
				<tr><td>
						{!!Form::open(['url' => 'tags/'.$tag->name])!!}
						
							{!!Form::text('name', $tag->name,['class' => 'form-control'])!!}
							
					</td>
					<td>
							{!!Form::button('<i class="fa fa-plus"></i> Update',['class' => 'btn btn-default', 'type' => 'submit'])!!}

						{!!Form::close()!!}
				
					</td>
			@else
				<tr><td>
						<a href="/tags/{{ $thetag->name }}"><button class="btn btn-default">{{ $thetag->name }} ({{ $thetag->articles->count() }})</button></a>
					</td>
					<td>
						<a href="/tags/{{ $thetag->name }}/edit"><button class="btn btn-default">Edit</button></a>
					</td>
			@endif
					<td>
						{!!Form::open(['method' => 'DELETE', 'url' => 'tags/'.$thetag->name ])!!}

		      				{!!Form::button('<i class="fa fa-btn fa-trash"></i>Delete', array('id' => 'delete', 'class' => 'btn btn-danger'))!!}

		    			{!!Form::close()!!}
					</td>
				</tr>
		@endforeach
	</table>
@stop

@section('footer')

@include('ConfirmDelete')

@stop