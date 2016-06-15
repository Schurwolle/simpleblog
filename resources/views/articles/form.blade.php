	@section ('head')
		<style type="text/css">
			#image {
				background-image: url({{ $article->id != null ? '/pictures/'.$article->id : '/img/placeholder.jpg' }});
			}
			#thumbnail {
				background-image: url({{ $article->id != null ? '/pictures/'.$article->id.'thumbnail' : '/img/placeholder.jpg' }});
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
							'data-parsley-unique'			=>  $article->title ? 'article$title#'.$article->title : 'article$title#',
							'data-parsley-unique-message'   => 'That title has already been taken.',
							'data-parsley-trigger' 			=> 'keyup focusout',
						]
					) 
	!!}
	{!! Form::text('slug', null, 
						[
							'id' 			      			=> 'slug', 
							'style' 			  			=> 'display:none;',
							'data-parsley-unique'			=>  $article->title ? 'article$slug#'.$article->slug : 'article$slug#',
							'data-parsley-unique-message'   => 'That slug has already been taken.',
						]
					) 
	!!}
	</div>

	<div class="form-group">
	{!! Form::label('body', 'Body:') !!}
	{!! Form::textarea('body', null, ['id' =>'body' , 'class' => 'form-control', 'required']) !!}
	</div>
	
	<div class="form-group">
    {!! Form::label('image', 'Image:') !!} <a href="#" id ="imginfo" title="The image will be shown resized and cropped in the article page and bxslider slideshow, but original image will be shown in lightbox2 modal window." onclick="return false"><i class="fa fa-info-circle"></i></a>
    <div id="image"></div>
    {!!Form::text('img', null, 
    				[
    					'id' => 'img', 
    					'style' => 'display:none;', 
    					 $article->title ? null : 'required', 
    					'data-parsley-required-message' => 'The image is required.'
    				]
    			)
    !!}
	</div>

	<div class="form-group">
    {!! Form::label('thumbnail', 'Thumbnail Image:') !!} <a href="#" id ="thumbinfo" title="The thumbnail image will be shown only resized and cropped, as a thumbnail image in popular articles section."  onclick="return false"><i class="fa fa-info-circle"></i></a>
    <div id="thumbnail"></div>
    {!!Form::text('thumbnailImage', null,
    				[
    				 	'id' => 'thumbnailImage', 
    				 	'style' => 'display:none;', 
    				 	 $article->title ? null : 'required', 
    				 	'data-parsley-required-message' => 'The thumbnail image is required.'
    				]
    			)
    !!}
	</div>

	@section('footer')
	    <script src="/ckeditor/ckeditor.js"></script>
		<script>
			$('#tag_list').select2({

				placeholder: 'Choose a tag or type your own',
				allowClear: true, 
				width: '100%',
				tags: true,
			    tokenSeparators: [",", " "],
			    createTag: function(newTag) {
			     
			        return {
			            id: 'new' + newTag.term,
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
			$('#title').on('keyup change', function(){
				setTimeout(createSlug, 1);
			});
			$('#slug').on('keyup', createSlug);
			function createSlug(){
				var slug = $('#title').val()
										.toLowerCase()
										.replace(/[^\w ]+/g,'')
										.replace(/_+/g,'')
										.trim()
        								.replace(/ +/g,'-')
        		;
				$('#slug').val(slug);
				var arrTitleErr = window.ParsleyUI.getErrorsMessages($('#title').parsley());
				if (arrTitleErr.length == 0)
				{	
					$('#slug').parsley().validate();
				}
	  		}
	  		$('#tag_list').on('change', function(){
	  			$('#tag_list').parsley().validate();
	  		});
		</script>

		<script type="text/javascript">
		$(function() {
	  		$('#imginfo').balloon({position: "right"});
	  		$('#thumbinfo').balloon({position: "right"});
	  		$('#addImgsinfo').balloon({position: "right"});
	  		$('#deleteinfo').balloon({position: "right"});
	  		$('#tagsinfo').balloon({position: "right"});
		});
		var shown = true;
		$('#imginfo, #thumbinfo, #addImgsinfo, #deleteinfo, #tagsinfo').on('click', function() {
			shown ? $(this).hideBalloon() : $(this).showBalloon();
			shown = !shown;
		});
		</script>

		<script type="text/javascript">
			$('#addImgs').on('change', function(){
				if($('table.additional').find("input:checkbox:not(:checked)").length + $("#addImgs")[0].files.length > 5)
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
				$('#addImgs').after('<table><tr id="selected" class="additional"></tr></table>')
				for(var i = 0;i < $('#addImgs')[0].files.length;i++)
				{
					var reader = new FileReader();
					reader.onload = function (img){
						$('#selected').append('<td><a href="' +img.target.result +'" data-lightbox="selected"><img src="' +img.target.result +'"></a></td>')
					}
					reader.readAsDataURL($('#addImgs')[0].files[i]);
				}

			});
			function erase()
			{
				$('#selected').parents('table').remove();
				$('#addImgs').val('');
			}
			$('table.additional').find("input:checkbox(:checked)").on('change', function () {
				if($('table.additional').find("input:checkbox:not(:checked)").length + $("#addImgs")[0].files.length > 5)
				{
					swal({ title: "Error!", text: "Maximum number of additional images is 5.", timer: 2000, showConfirmButton: false, type:"error" });
					$(this).prop('checked', true);
				}
			})
		</script>
		<script type="text/javascript">
			var warning = false;
			var field = CKEDITOR.instances['body'];
			$(':input').on('change keyup', function(){
				warning = true;
				if($(this).attr('type') != 'file') 
				{
					field = $(this);	
				}
			});
			CKEDITOR.instances['body'].on('change', function(e) {
			    warning = true;
			    field = $(this);
			});
			$('#submit').on('click', function() {
				warning = false;
			});
			$(window).on("beforeunload", function() {
				if(warning == true)
				{
					field.focus();
					return ('You have unsaved changes!');
				}
			});
		</script>
	@stop
