@extends('layouts.app')

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

	{!! Form::open(['url' => $user->name.'/updateavatar', 'files' => 'true', 'data-parsley-validate']) !!}

		<div class="form-group">
		    {!! Form::label('cropper', 'New Avatar:') !!}
    		<div id="cropper"></div>
    		{!!Form::text('newAvatar', null, ['id' => 'newAvatar', 'style' => 'visibility:hidden;', 'required', 'data-parsley-required-message' => 'New Avatar is required.', 'data-parsley-trigger' => 'input'])!!}
		</div>
		
		{!!Form::button('<i class="fa fa-plus"></i> Update', ['class' => 'btn btn-primary', 'style' => 'width: 100px;', 'type' => 'submit'])!!}

	{!! Form::close() !!}



@endsection

@section('footer')
@include('parsleyfooter')
		<script>
		$('#newAvatar').hide();
		var cropperOptions = {
			uploadUrl:'/upload',
			cropUrl: '/crop',
			modal: true,
			rotateControls:false,
			doubleZoomControls:false,
			enableMousescroll:true,
			outputUrlId:'newAvatar',

		}		
		    var cropperHeader = new Croppic('cropper', cropperOptions);
		</script>

@endsection