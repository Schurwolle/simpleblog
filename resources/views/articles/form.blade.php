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
	{!! Form::text('slug', null, ['id' => 'slug', 'style' => 'display:none;']) !!}
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
    {!!Form::text('img', null, ['id' => 'img', 'style' => 'display:none;'])!!}
	</div>

	<div class="form-group">
    {!! Form::label('thumbnail', 'Thumbnail Image:') !!} <a href="#" id ="thumbinfo" title="The thumbnail image will be shown only resized and cropped, as a thumbnail image in popular articles section."  onclick="return false"><i class="fa fa-info-circle"></i></a>
    <div id="thumbnail"></div>
    {!!Form::text('thumbnailImage', null, ['id' => 'thumbnailImage', 'style' => 'display:none;'])!!}
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
			$('#title').on('change', createSlug);
			$('#slug').on('change', createSlug);

			function createSlug(){
				var slug = $('#title').val()
										.toLowerCase()
										.replace(/[^\w ]+/g,'')
										.replace(/_+/g,'')
										.trim()
        								.replace(/ +/g,'-')
        		;
				$('#slug').val(slug);
			}
		</script>

		<script type="text/javascript">
		$(function() {
	  		$('#imginfo').balloon({position: "right"});
	  		$('#thumbinfo').balloon({position: "right"});
	  		$('#addImgsinfo').balloon({position: "right"});
	  		$('#deleteinfo').balloon({position: "right"});
		});
		</script>

		<script type="text/javascript">
			$('#addImgs').on('change', function(){
				if($("input:checkbox:not(:checked)").length + $("#addImgs")[0].files.length > 5)
				{
					swal({ title: "Error!", text: "Maximum number of additional images is 5.", timer: 2000, showConfirmButton: false, type:"error" });
					return erase();
				}

				for(var i = 0;i < $('#addImgs')[0].files.length;i++)
				{	
					var type = ($('#addImgs')[0].files[i].type);
					if(type != "image/jpeg" && type != "image/png" && type != "image/bmp" && type != "image/gif" && type != "image/svg")
					{
						swal({ title: "Error!", text: "File number "+ (i+1)+ " is not an image.", timer: 2000, showConfirmButton: false, type:"error" });
						return erase();
					}
					if($('#addImgs')[0].files[i].size > 2048000)
					{
						swal({ title: "Error!", text: "Image number "+ (i+1) +" is too big. Maximum size allowed is 2048KB.", timer: 2000, showConfirmButton: false, type:"error" });
						return erase();
					}
				}
				$('#selected').parents('table').remove();
				$('#addImgs').after('<table><tr id ="selected"></tr></table>')
				for(var i = 0;i < $('#addImgs')[0].files.length;i++)
				{
					var reader = new FileReader();
					reader.onload = function (img){
						$('#selected').append('<td style="padding-right: 10px;padding-top: 10px;"><a href="' +img.target.result +'" data-lightbox="selected"><img style="max-width:100px; max-height:100px;" src="' +img.target.result +'"></a></td>')
					}
					reader.readAsDataURL($('#addImgs')[0].files[i]);
				}

			});
			function erase()
			{
				$('#selected').parents('table').remove();
				$('#addImgs').val('');
			}
			$("input:checkbox(:checked)").on('change', function () {
				if($("input:checkbox:not(:checked)").length + $("#addImgs")[0].files.length > 5)
				{
					swal({ title: "Error!", text: "Maximum number of additional images is 5.", timer: 2000, showConfirmButton: false, type:"error" });
					$(this).prop('checked', true);
				}
			})
		</script>
	@stop
