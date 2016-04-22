	<div class="form-group">
	{!! Form::label('addImgs[]', 'Additional Images:') !!} <a href="#" id ="addImgsinfo" title="Additional images are optional. Maximum number is 5."  onclick="return false"><i class="fa fa-info-circle"></i></a>
	{!! Form::file('addImgs[]', ['multiple', 'id' => 'addImgs']) !!}
	</div>

	<div class="form-group">
	{!! Form::label('tag_list', 'Tags:') !!} <a href="#" id ="tagsinfo" title="You can choose a tag from the list or just type a new one. Note that tags can contain only alphanumeric characters, and will be saved in lower case."  onclick="return false"><i class="fa fa-info-circle"></i></a>
	{!! Form::select('tag_list[]', $tags, null, ['id' => 'tag_list', 'class' => 'form-control', 'multiple', 'data-parsley-arrayalphanum']) !!}
	</div>