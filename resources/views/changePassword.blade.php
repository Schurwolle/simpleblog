@extends('layouts.app')

@section('head')
	 @include('parsleyhead')
@stop


@section('content')
	<h1>Change Your Password</h1>
	<hr>

	@include('errors.list')

	{!! Form::open(['url' => $user->name.'/updatepassword', 'data-parsley-validate' ]) !!}
		<div class="form-group">
		{!! Form::label('oldPassword', 'Current password:') !!}

		{!! Form::password('oldPassword', 
							[
								'class' 						=> 'form-control',  
								'placeholder'					=> 'Enter current password',
								'required',
								'data-parsley-required-message' => 'Current password is required.',
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
								'data-parsley-required-message' => 'New password is required.',
								'data-parsley-minlength'		=> '6',
								'data-parsley-minlength-message'=> 'New password should be at least 6 characters long.',
								'data-parsley-trigger' 			=> 'change focusout'
								]) !!}

		</div>

		<div class="form-group">
		{!! Form::label('confirmPassword', 'Confirm new password:') !!}

		{!! Form::password('confirmPassword', 
							[
								'class' 						=> 'form-control',  
								'placeholder'					=> 'Confirm new password',
								'required',
								'data-parsley-required-message' => 'Confirming new password is required.',
								'data-parsley-minlength'		=> '6',
								'data-parsley-minlength-message'=> 'New password should be at least 6 characters long.',
								'data-parsley-trigger' 			=> 'change focusout',
								'data-parsley-equalto'			=> '#newPassword',
								'data-parsley-equalto-message'  => 'Passwords do not match.'
								]) !!}

		</div>

		<div class="form-group">
			{!! Form::button('<i class="fa fa-plus"></i> Update', ['class' => 'btn btn-primary form-control', 'type' => 'submit']) !!}
		</div>
	{!!Form::close()!!}
@stop



@section('footer')	
	@include('parsleyfooter')
	<script type="text/javascript">
		document.getElementById("oldPassword").focus();
	</script>
@stop