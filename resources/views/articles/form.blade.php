	@section('head')

	 	<style>
	 		#thumbnail {
			width: 200px;
			height: 150px;
			position:relative;
			border: solid 1px black;
		}
			#image {
			width: 650px;
			height: 290px;
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
    <div id="image"></div>
    {!!Form::text('img', null, ['id' => 'img', 'style' => 'visibility:hidden;'])!!}
	</div>

	<div class="form-group">
    {!! Form::label('thumbnail', 'Thumbnail Image:') !!}
    <div id="thumbnail"></div>
    {!!Form::text('thumbnailImage', null, ['id' => 'thumbnailImage', 'style' => 'visibility:hidden;'])!!}
	</div>

	<div class="form-group">
	{!! Form::label('tag_list', 'Tags:') !!}
	{!! Form::select('tag_list[]', $tags, null, ['id' => 'tag_list', 'class' => 'form-control', 'multiple']) !!}
	</div>

	@section('footer')
		<script src="/select2.min.js"></script>
	    <script src="/ckeditor/ckeditor.js"></script>
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
		$('#img').hide();
		$('#thumbnailImage').hide();
		var thumbnailOptions = {
			uploadUrl:'/upload',
			cropUrl: '/crop',
			modal: true,
			rotateControls:false,
			enableMousescroll:true,
			outputUrlId:'thumbnailImage'
		}		
		var cropperThumbnail = new Croppic('thumbnail', thumbnailOptions);

		var imageOptions = {
			uploadUrl:'/upload',
			cropUrl: '/crop',
			modal: true,
			rotateControls:false,
			enableMousescroll:true,
			outputUrlId:'img'
		}
		var cropperImage = new Croppic('image', imageOptions);
		
		</script>
	@stop
