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
							'data-parsley-maxlength'		=> '78',
							'data-parsley-maxlength-message'=> 'Title cannot be longer than 78 characters.',
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
	{!! Form::textarea('body-hidden', null, 
							[
								'id' 							=> 'body-hidden', 
								'class' 						=> 'form-control', 
								'style' 						=> 'display:none', 
								'required', 
								'data-parsley-required-message' => 'Body is required.',
								'data-parsley-maxlength'		=> '64443',
								'data-parsley-maxlength-message'=> 'Body cannot be longer than 64443 characters.',
								'data-parsley-ckeimgs'			=>  csrf_token(),
								'data-parsley-ckeimgs-message'  => 'Your image is not linked correctly.',
							]
						) 
	!!}

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
			$.fn.select2.defaults.defaults['language'].inputTooLong = function(){
		  		return 'Tag cannot be longer than 32 characters.';
			}
			$.fn.select2.defaults.defaults['language'].noResults = function() {
				return 'Tag can contain only aplhanumeric characters.';
			};
			$('#tag_list').select2({

				placeholder: 'Choose a tag or type your own',
				allowClear: true, 
				maximumSelectionLength: 8,
				maximumInputLength: 32,
				width: '100%',
				tags: true,
			    tokenSeparators: [",", " "],
			    createTag: function(newTag) {
			    	if(newTag.term.match(/^[a-zA-Z0-9]+$/g))
			    	{
				        return {
				            id: 'new' + newTag.term,
				            text: newTag.term + ' (new)'
				        };
				    }
			    }
			});
			
		</script>
		<script>
			CKEDITOR.replace('body');
			CKEDITOR.config.basicEntities = false;
			CKEDITOR.config.entities = false;
			window.onload = function()
			{
				$('#body-hidden').val(CKEDITOR.instances['body'].getData());
			}
			CKEDITOR.instances['body'].on('blur', CKEParsley);
			CKEDITOR.instances['body'].on('change', CKEParsley);
			
			function CKEParsley(){
				$('#body-hidden').val(CKEDITOR.instances['body'].getData());
				$('#body-hidden').parsley().validate();
			}
		</script>
		@include('parsleyfooter')
		<script>
		var thumbnailOptions = {
			uploadUrl:'/upload',
			cropUrl: '/crop',
			modal: true,
			rotateControls:false,
			enableMousescroll:true,
			outputUrlId:'thumbnailImage',
			onAfterImgCrop:	function() { 
				arrThisErr = window.ParsleyUI.getErrorsMessages($('#thumbnailImage').parsley());
				if(arrThisErr.length > 0)
				{
					$('#thumbnailImage').parsley().validate();
				}
				$('#thumbnail').css('background-image', 'none')
			},
			onReset: function(){ 
				$('#thumbnailImage').parsley().validate();
				var id = {{$article->id}}
				$('#thumbnail').css('background-image', id ? 'url(/pictures/'+ id +'thumbnail)' : 'url(/img/placeholder.jpg)');
			}
		}		
		var cropperThumbnail = new Croppic('thumbnail', thumbnailOptions);

		var imageOptions = {
			uploadUrl:'/upload',
			cropUrl: '/crop',
			modal: true,
			rotateControls:false,
			enableMousescroll:true,
			outputUrlId:'img',
			onAfterImgCrop:	function() { 
				arrThisErr = window.ParsleyUI.getErrorsMessages($('#img').parsley());
				if(arrThisErr.length > 0)
				{
					$('#img').parsley().validate();
				}
				$('#image').css('background-image', 'none')
			},
			onReset: function(){ 
				$('#img').parsley().validate();
				var id = {{$article->id}}
				$('#image').css('background-image', id ? 'url(/pictures/'+ id +')' : 'url(/img/placeholder.jpg)');
			}
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
				
				$('#selected').parents('table').remove();
				$('.my_error_container').remove();

				if($('table.additional').find("input:checkbox:not(:checked)").length + $("#addImgs")[0].files.length > 5)
				{
					swal({ title: "Error!", text: "Maximum number of additional images is 5.", timer: 2000, showConfirmButton: false, type:"error" });
					$('#addImgs').val('');
					return; 
				}

				for(var i = 0;i < $('#addImgs')[0].files.length;i++)
				{	
					var type = ($('#addImgs')[0].files[i].type);
					if(type != "image/jpeg" && type != "image/png" && type != "image/bmp" && type != "image/gif" && type != "image/svg")
					{
						swal({ title: "Error!", text: "File number " +(i+1)+ " is not an image.", timer: 2000, showConfirmButton: false, type:"error" });
						$('#addImgs').val('');
						return;
					}
					if($('#addImgs')[0].files[i].size > 2048000)
					{
						swal({ title: "Error!", text: "Image number " +(i+1)+ " is too big. Maximum size allowed is 2048KB.", timer: 2000, showConfirmButton: false, type:"error" });
						$('#addImgs').val('');
						return; 
					}
				}

				$('#addImgs').after('<tr id="selected" class="additional new_add_imgs"></tr>')

				for(var i = 0;i < $('#addImgs')[0].files.length;i++)
				{
					var reader = new FileReader();
					reader.onload = function (img){
						$('#selected').append('<td align="middle"><a href="' +img.target.result+ '" data-lightbox="selected"><img src="' +img.target.result+ '"></a></td>');
					}
					reader.readAsDataURL($('#addImgs')[0].files[i]);					
				}

				if($('#addImgs')[0].files.length > 1)
				{
					var ord_num = $('table.additional').find('input:checkbox:not(:checked)').length;
					$('#selected').after('<tr id="checkboxes" class="new_add_imgs"></tr>');
					for(i = 0; i < $('#addImgs')[0].files.length; i++)
					{
						$('#checkboxes').append('<td align="middle"><input type="checkbox" name="images[' +i+ ']" data-parsley-multiple="checkbox_group" data-parsley-errors-container=".my_error_container" data-parsley-class-handler=".my_error_container" data-parsley-error-message="Additional images are not ordered."></td>');
						if(i == $('#addImgs')[0].files.length - 1)
						{
							$('[name="images[' +i+ ']"').attr({
								'data-parsley-mincheck': $('#addImgs')[0].files.length,
								'required':"required"
							});
							$('#checkboxes').after('<div class="my_error_container"></div>');
						}
					}

					$('#selected').after('<tr id="message" class="new_add_imgs"><td colspan="5" align="middle">Order images from first to last: </td></tr>');

					$('#checkboxes').find("input:checkbox").on('change', function(){
						if($(this).prop('checked') == true)
						{
							$(this).before('<span>' +(ord_num + $('#checkboxes').find("input:checkbox:checked").length)+ ' </span>');
							$(this).attr('value', ord_num + $('#checkboxes').find("input:checkbox:checked").length);
						} else {
							if($(this).attr('value') != ord_num + $('#checkboxes').find("input:checkbox:checked").length + 1)
							{
								return $(this).prop('checked', true);
							}
							$(this).parents('td').find('span').remove();
							$(this).removeAttr('value');
						}
					});
					$('#checkboxes').after('<tr class="new_add_imgs"><td colspan="5"><input type="checkbox" id="auto_order"> Order automatically.</td></tr>');
					$('#auto_order').on('change', function() {

						$('#checkboxes').find('span').remove();
						$('#checkboxes').find("input:checkbox").removeAttr('checked');

						if($(this).prop('checked') == true)
						{
							for(var i = 0; i < ord_num + $('#addImgs')[0].files.length; i++)
							{
								$('#checkboxes').find("input:checkbox").eq(i).prop({
									'checked': true,
									'value': ord_num + $('#checkboxes').find("input:checkbox:checked").length
								});
								$('#checkboxes').find("input:checkbox").eq(i).before('<span>' +(ord_num + $('#checkboxes').find("input:checkbox:checked").length)+ ' </span>');
							}
						} else {
							$('#checkboxes').find("input:checkbox").removeAttr('value');
						}
					});
				}
				$('.new_add_imgs').wrapAll('<table></table>');
			});

			$('table.additional').find("input:checkbox").on('change', function () {
				if($(this).prop('checked') != true)
				{
					if($('table.additional').find("input:checkbox:not(:checked)").length + $("#addImgs")[0].files.length > 5)
					{
						swal({ title: "Error!", text: "Maximum number of additional images is 5.", timer: 2000, showConfirmButton: false, type:"error" });
						$(this).prop('checked', true);
						return;
					}
					if($('#checkboxes').length)
					{
						for(var i = 0; i < $('#checkboxes').find('input:checkbox:checked').length; i++)
						{
							$('#checkboxes').find('input:checkbox:checked').eq(i).attr('value', +$('#checkboxes').find('input:checkbox:checked').eq(i).attr('value') + 1);
							$('#checkboxes').find('span').eq(i).text(parseInt($('#checkboxes').find('span').eq(i).text(), 10) + 1 + ' ');
						}
					}
				} else {
					if($('#checkboxes').length)
					{
						for(var i = 0; i < $('#checkboxes').find('input:checkbox:checked').length; i++)
						{
							console.log($('#checkboxes').find('span').eq(i).text());
							$('#checkboxes').find('input:checkbox:checked').eq(i).attr('value', +$('#checkboxes').find('input:checkbox:checked').eq(i).attr('value') - 1);
							$('#checkboxes').find('span').eq(i).text(parseInt($('#checkboxes').find('span').eq(i).text(), 10) - 1 + ' ');
						}
					}
				}
			})
		</script>
		<script type="text/javascript">
		$(window).on("load", function() {
			$(':input').each(function(){
				window["value" + $(this).attr('name')] = $(this).val();
			});
		});
			var warning = [];
			var field = CKEDITOR.instances['body'];
			$(':input').on('change keyup', function(){
				if($(this).val() != window["value" + $(this).attr('name')])
				{
					warning[$(this).attr('name')] = true;
					if($(this).attr('type') != 'file') 
					{
						field = $(this);
					}
				} else {
					warning[$(this).attr('name')] = false;
				}
			});
			CKEDITOR.instances['body'].on('change', function(e) {
				if(CKEDITOR.instances['body'].getData() != valuebody)
				{
					warning['CKE'] = true;
			    	field = $(this);
			    } else {
			    	warning['CKE'] = false;
			    }
			});
			$('#sub').on('click', function() {
				var old = warning;
				warning = false;
				if(old == true)
				{
					setTimeout(function(){
						warning = true;
					}, 500);
				}
			});
			$(window).on("beforeunload", function() {
				for (var key in warning) {
					if(warning[key] == true)
					{
						field.focus();
						return ('You have unsaved changes!');
					}
				}
			});
		</script>
	@stop
