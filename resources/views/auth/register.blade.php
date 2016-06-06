@extends('layouts.app')

@section('sides')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading auth">Register</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" enctype="multipart/form-data" method="POST" action="{{ url('/register') }}" data-parsley-validate="data-parsley-validate">
                        {!! csrf_field() !!}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Name</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="Enter your name"required="required" data-parsley-required-message="Name is required."data-parsley-minlength="2" data-parsley-minlength-message="Name should be at least 2 characters long." data-parsley-unique="user$name" data-parsley-unique-message="That name has already been taken." data-parsley-trigger="change focusout">

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Enter your e-mail address" required="required" data-parsley-required-message="E-mail address is required." data-parsley-type="email" data-parsley-type-message="This e-mail address is not valid." data-parsley-unique="user$email" data-parsley-unique-message="That email has already been taken." data-parsley-trigger="change focusout">

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input type="password" class="form-control" name="password" id="password" required="required" placeholder="Enter your password" data-parsley-required-message="Password is required." data-parsley-minlength="6" data-parsley-minlength-message="Password should be at least 6 characters long." data-parsley-trigger="change focusout">

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Confirm Password</label>

                            <div class="col-md-6">
                                <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm your password"required="required" data-parsley-required-message="Confirming password is required." data-parsley-minlength="6" data-parsley-minlength-message="Password should be at least 6 characters long." data-parsley-equalto="#password" data-parsley-equalto-message="Passwords do not match." data-parsley-trigger="change focusout">

                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('avatar') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label" for="avatar">Avatar Image:</label>
                            <div class="col-md-6">
                                <div id="cropper"></div>
                                @if ($errors->has('avatar'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('avatar') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-user"></i> Register
                                </button> or
                                <a href="/login/facebook"> <div class="btn btn-md btn-primary"> <i class="fa fa-facebook"></i> Register with Facebook </div></a>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
    @include('parsleyfooter')
    <script>
    var cropperOptions = {
        uploadUrl:'/upload',
        cropUrl: '/crop',
        modal: true,
        rotateControls:false,
        doubleZoomControls:false,
        enableMousescroll:true
    }       
        var cropperHeader = new Croppic('cropper', cropperOptions);
    </script>
@endsection