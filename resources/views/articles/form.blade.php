	@section('head')
	 	@include('parsleyhead')
	 	<link rel="stylesheet" href="/croppic.css"/>
	 	<style>
	 		#cropper {
			width: 200px;
			height: 150px;
			position:relative;
			border: solid 1px black;
		}
	 	</style>
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
		
	</div>
	<div class="form-group">
    {!! Form::label('image', 'Image:') !!}
    {!! Form::file('image', null) !!}
	</div>

	<div class="form-group">
    {!! Form::label('cropper', 'Thumbnail Image:') !!}
    <div id="cropper"></div>
    {!!Form::text('thumbnailImage', null, ['id' => 'thumbnailImage', 'style' => 'visibility:hidden;'])!!}
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
		@include('parsleyfooter')
		<script src="/jquery.mousewheel.min.js"></script>
		<script src="/croppic.min.js"></script>
		<script>
		var cropperOptions = {
			uploadUrl:'/upload',
			cropUrl: '/crop',
			modal: true,
			rotateControls:false,
			enableMousescroll:true,
			outputUrlId:'thumbnailImage'
		}		
		    var cropperHeader = new Croppic('cropper', cropperOptions);
		</script>
	@stop
