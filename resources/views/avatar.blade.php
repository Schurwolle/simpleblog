@extends('layouts.app')

@section('head')
	<link rel="stylesheet" href="/croppic.css"/>

	<style>
	#update {
	    width: 100px;
	}
	#cropper {
		width: 100px;
		height: 100px;
		position:relative;
		border: solid 1px black;
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
		    {!! Form::label('cropper', 'New Avatar:') !!}
    		<div id="cropper"></div>
		</div>
		
		{!!Form::submit('Update', ['class' => 'btn btn-primary', 'id' => 'update'])!!}

	{!! Form::close() !!}



@endsection

@section('footer')
	<script src="/jquery.mousewheel.min.js"></script>
	<script src="/croppic.min.js"></script>
		<script>
		var cropperOptions = {
			uploadUrl:'/upload',
			cropUrl: '/crop',
			modal: true,
			rotateControls:false,
			doubleZoomControls:false,
			enableMousescroll:true

		}		
		    var cropperHeader = new Croppic('cropper', cropperOptions);
		</script>

@endsection