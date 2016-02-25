	<div class="form-group">
	{!! Form::label('published_at', 'Publish On:') !!}

	{!! Form::input('date', 'published_at', null,
			 [
			 	'class' => 'form-control', 
			 	'required', 
			 	'data-parsley-required-message' => 'Must be a valid date.',
			 	'data-parsley-trigger' => 'change focusout'
			 	]) !!}
	</div>
