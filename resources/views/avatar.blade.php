@extends('layouts.app')

@section('head')
	<style>
	#update {
	    width: 6.5em;
	}
	</style>
@endsection


@section('content')

	@include('errors.list')
	@if (file_exists('pictures/'.$user->name))
		<h1>Change Avatar Image</h1>
		<hr>
		<strong>Current Avatar:</strong><br>
		{{ Html::image(('pictures/'.$user->name)) }}
	@else
		<h1>Upload Avatar Image</h1>
		<hr>
	@endif

	{!! Form::open(['url' => $user->name.'/updateavatar', 'files' => 'true']) !!}

		<div class="form-group">
		    {!! Form::label('newAvatar', 'New Avatar:') !!}
		    {!! Form::file('newAvatar', null) !!}
		</div>
		<div class="form-group">
			<div id="cropper"></div>
		</div>
		{!!Form::submit('Update', ['class' => 'btn btn-primary', 'id' => 'update'])!!}

	{!! Form::close() !!}



@endsection