	@section('head')
	 <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2-rc.1/css/select2.min.css" rel="stylesheet" />
	 {!!Html::style('/parsley.css')!!}
	@stop


	<div class="form-group">
	{!! Form::label('title', 'Title:') !!}

	{!! Form::text('title', null, 
						[
							'class' 						=> 'form-control',  
							'placeholder'					=> 'Title of the Article',
							'required',
							'data-parsley-required-message' => 'Title is required.',
							'data-parsley-trigger' 			=> 'change focusout'
							]) !!}

	</div>

	<div class="form-group">
	{!! Form::label('body', 'Body:') !!}
	{!! Form::textarea('body', null, ['id' =>'body' , 'class' => 'form-control', 'required']) !!}
	</div>

	<div class="form-group">
    {!! Form::label('Image:') !!}
    {!! Form::file('image', null) !!}
	</div>

	<div class="form-group">
	{!! Form::label('tag_list', 'Tags:') !!}
	{!! Form::select('tag_list[]', $tags, null, ['id' => 'tag_list', 'class' => 'form-control', 'multiple']) !!}
	</div>

	@section('footer')
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2-rc.1/js/select2.min.js"></script>
    <script src="//cdn.ckeditor.com/4.5.7/standard/ckeditor.js"></script>
	<script>
		$('#tag_list').select2({

			placeholder: 'Choose a tag or type your own', 
			tags: true,
		    tokenSeparators: [",", " "],
		    createTag: function(newTag) {
		     
		        return {
		            id: 'new:' + newTag.term,
		            text: newTag.term + ' (new)'
		        };
		    }
		});
	</script>
	<script>
		CKEDITOR.replace('body');
	</script>
	<script type="text/javascript">

        window.ParsleyConfig = {
            errorsWrapper: '<div></div>',
            errorTemplate: '<div class="alert alert-danger parsley" role="alert"></div>'
        };
    </script>
    {{Html::script('/parsley.min.js')}}
	@stop
