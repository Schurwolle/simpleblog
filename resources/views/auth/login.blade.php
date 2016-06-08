@extends('layouts.app')

@section('head')

    <style>
        .success {
            margin:0 auto;
            width:800px;
        }
        .danger {
            margin:0 auto;
            width:370px;
        }
    </style>

@stop

@section('sides')
<br>
<br>
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Login</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}" data-parsley-validate="data-parsley-validate">
                        {!! csrf_field() !!}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Enter your e-mail address" required="required" data-parsley-required-message="E-mail address is required." data-parsley-type="email" data-parsley-type-message="This e-mail address is not valid." data-parsley-trigger="change focusout">

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
                                <input type="password" class="form-control" name="password" placeholder="Enter your password" required="required" data-parsley-required-message="Password is required." data-parsley-minlength="6" data-parsley-minlength-message="Password should be at least 6 characters long." data-parsley-trigger="change focusout">

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember"> Remember Me
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-sign-in"></i> Login
                                </button> or 
                                <a href="/login/facebook"> <div class="btn btn-md btn-primary"> <i class="fa fa-facebook"></i> Login with Facebook </div></a>
                                <div>
                                <a href="{{ url('/password/reset') }}">Forgot Your Password?</a>
                                </div>
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
@endsection
