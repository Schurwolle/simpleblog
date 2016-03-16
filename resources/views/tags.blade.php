@extends('layouts.app')

@section('content')

	<h1>Tags:</h1>
	<hr>
	<table class="table table-striped table-bordered">
	@if($tags->count() > 0)
		@foreach ($tags as $tag)
			<tr><td>
					<a href="/tags/{{ $tag->name }}"><button class="btn btn-default">{{ $tag->name }} ({{ $tag->articles->count() }})</button></a>
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

@stop