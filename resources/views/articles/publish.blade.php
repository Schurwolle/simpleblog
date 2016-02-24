	<div class="form-group">
	{!! Form::label('published_at', 'Publish On:') !!}
	{!! Form::input('date', 'published_at', null,
			 [
			 	'class' => 'form-control', 
			 	'required', 
			 	'data-parsley-required-message' => 'Publish on field is required.', 
			 	'data-parsley-type' => 'date',  
			 	'data-parsley-trigger' => 'change focusout']) !!}
	</div>