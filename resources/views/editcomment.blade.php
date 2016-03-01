@extends('layouts.app')

@section('content')
<h3>Edit Your Comment:</h3>

		<div class="panel-body">
		{!! Form::model($comment, ['method' => 'PATCH', 'url' => '/comment/'.$comment->id ] ) !!}
	        <div class="form-group">
	        {!! Form::textarea('body', null, ['id' =>'body' , 'class' => 'form-control', 'required']) !!}
	        </div>
	        @include('articles.submit', ['submitButton' => 'Update', 'class' => 'btn btn-primary'])
	      {!!Form::close()!!}
	    </div>

@endsection