@extends('layouts.app')

@section('head')
	 <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2-rc.1/css/select2.min.css" rel="stylesheet" />
	 {!!Html::style('/parsley.css')!!}
@stop


@section('content')
	@include('errors.list')

	{!! Form::open(['url' => $user->name.'/updatepassword', 'data-parsley-validate' ]) !!}
		<div class="form-group">
		{!! Form::label('oldPassword', 'Current password:') !!}

		{!! Form::password('oldPassword', 
							[
								'class' 						=> 'form-control',  
								'placeholder'					=> 'Enter current password',
								'required',
								'data-parsley-trigger' 			=> 'change focusout'
								]) !!}

		</div>
		<div class="form-group">
		{!! Form::label('newPassword', 'New password:') !!}

		{!! Form::password('newPassword', 
							[
								'class' 						=> 'form-control',  
								'placeholder'					=> 'Enter new password',
								'required',
								'data-parsley-minlength'		=> '6',
								'data-parsley-trigger' 			=> 'change focusout'
								]) !!}

		</div>

		<div class="form-group">
			{!! Form::button('Change Password', ['class' => 'btn btn-primary form-control', 'type' => 'submit']) !!}
		</div>
	{!!Form::close()!!}
@stop



@section('footer')	
	<script type="text/javascript">

        window.ParsleyConfig = {
            errorsWrapper: '<div></div>',
            errorTemplate: '<div class="alert alert-danger parsley" role="alert"></div>'
        };
    </script>
    {{Html::script('/parsley.min.js')}}
@stop