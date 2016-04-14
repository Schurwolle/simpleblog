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
    {!! Form::label('image', 'Image:') !!} <a href="#" id ="imginfo" title="The image will be shown resized and cropped in the article page and bxslider slideshow, but original image will be shown in lightbox2 modal window." onclick="return false"><i class="fa fa-info-circle"></i></a>
    <div id="image"></div>
    {!!Form::text('img', null, ['id' => 'img', 'style' => 'visibility:hidden;'])!!}
	</div>

	<div class="form-group">
    {!! Form::label('thumbnail', 'Thumbnail Image:') !!} <a href="#" id ="thumbinfo" title="The thumbnail image will be shown only resized and cropped, as a thumbnail image in popular articles section."  onclick="return false"><i class="fa fa-info-circle"></i></a>
    <div id="thumbnail"></div>
    {!!Form::text('thumbnailImage', null, ['id' => 'thumbnailImage', 'style' => 'visibility:hidden;'])!!}
	</div>

	<div class="form-group">
	{!! Form::label('addImgs[]', 'Additional Images:') !!} <a href="#" id ="addImgsinfo" title="Additional images are optional. Maximum number is 5."  onclick="return false"><i class="fa fa-info-circle"></i></a>
	{!! Form::file('addImgs[]', ['multiple', 'id' => 'addImgs']) !!}
	</div>

	<div class="form-group">
	{!! Form::label('tag_list', 'Tags:') !!}
	{!! Form::select('tag_list[]', $tags, null, ['id' => 'tag_list', 'class' => 'form-control', 'multiple']) !!}
	</div>

	@section('footer')
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

		<script type="text/javascript">
		$(function() {
	  		$('#imginfo').balloon({position: "right"});
	  		$('#thumbinfo').balloon({position: "right"});
	  		$('#addImgsinfo').balloon({position: "right"});
		});
		</script>

		<script type="text/javascript">
			$('#addImgs').on('change', function(){
				if($("#addImgs")[0].files.length > 5)
				{
					swal({ title: "Error!", text: "Maximum number of additional images is 5.", timer: 2000, showConfirmButton: false, type:"error" });
					$('#addImgs').val('');
				} else {
					for(var i = 0;i < $('#addImgs')[0].files.length;i++)
					{	
						var type = ($('#addImgs')[0].files[i].type);
						if(type != "image/jpeg" && type != "image/png" && type != "image/bmp" && type != "image/gif" && type != "image/svg")
						{
							swal({ title: "Error!", text: "Invalid file type.", timer: 2000, showConfirmButton: false, type:"error" });
							return $('#addImgs').val('');
						}
						if($('#addImgs')[0].files[i].size > 2048000)
						{
							swal({ title: "Error!", text: "Maximum file size allowed is 2048KB.", timer: 2000, showConfirmButton: false, type:"error" });
							return $('#addImgs').val('');
						}
					}
				}
			});

		</script>
	@stop
