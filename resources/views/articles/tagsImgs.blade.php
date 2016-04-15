	<div class="form-group">
	{!! Form::label('addImgs[]', 'Additional Images:') !!} <a href="#" id ="addImgsinfo" title="Additional images are optional. Maximum number is 5."  onclick="return false"><i class="fa fa-info-circle"></i></a>
	{!! Form::file('addImgs[]', ['multiple', 'id' => 'addImgs']) !!}
	</div>

	<div class="form-group">
	{!! Form::label('tag_list', 'Tags:') !!}
	{!! Form::select('tag_list[]', $tags, null, ['id' => 'tag_list', 'class' => 'form-control', 'multiple']) !!}
	</div>