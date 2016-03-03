<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Blog</title>
    <link rel="alternate" type="application/rss+xml" href="{{ url('rss') }}"
        title="RSS Feed">
    <!-- Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700" rel='stylesheet' type='text/css'>

    <!-- Styles -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    {{-- <link href="{{ elixir('css/app.css') }}" rel="stylesheet"> --}}

    {!!Html::style('/default.css')!!}

    @yield('head')


</head>
<body id="app-layout">
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    <li><a href="{{ url('/articles') }}">Articles</a></li>

                    @if(Auth::check() && Auth::user()->isAdmin())
                        <li><a href="{{ url('/unpublished') }}">Unpublished Articles</a></li>
                    @endif
                    <li><a href="{{ url('/articles/create') }}">New Article</a></li>
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li><a href="{{ url('/login') }}">Login</a></li>
                        <li><a href="{{ url('/register') }}">Register</a></li>
                    @else
                    @if($latest)
                        <li><a href="/articles/{{ $latest->slug }}">{{str_limit($latest->title)}}</a></li>
                    @endif
                    @if(Auth::check() && Auth::user()->isAdmin())
                        <li><a href="{{ url('/users') }}">Users</a></li>
                    @endif
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a href="/{{ Auth::user()->name }}/profile">My profile</a></li>
                                <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
    <hr>
    <hr>

    <div class="container">
      @yield('sides')
      <div class="center">
        @if(Session::has('flash_message'))
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                {{Session::get('flash_message')}}
            </div>
        @endif
        @if(Session::has('alert_message'))
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                {{Session::get('alert_message')}}
            </div>
        @endif
        @yield('content')
      </div>
    </div>



    <footer class="footer">
      <div class="container">
         <div class="center">
          @if (Auth::check())
              <table class="navbar-text">
                  <tr>
                    <td>
                        <a href="{{ url('/articles') }}" style="border-right:solid 16px black; color: white;">Articles</a>
                    </td>
                    <td>
                        <a style="color: white;" href="{{ url('/logout') }}">Logout</a>
                    </td>
                   </tr>
               </table>
                <div class="pull-right">
                    {!! Form::open(array('url' => 'search', 'class'=>'form navbar-form navbar-right searchform')) !!}

                        {!! Form::text('search', null,
                                                    [
                                                    'required',
                                                    'class'         => 'form-control',
                                                    'placeholder'   => 'Search'
                                                    ]) !!}
                                                    
                         {!! Form::button('<i class="glyphicon glyphicon-search"></i>', 
                         [
                            'class'=>'btn btn-default', 
                            'type' => 'submit'
                         ]) !!}

                    {!! Form::close() !!}
                </div>
            @endif
          </div>
      </div>
    </footer>


    <!-- JavaScripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    {{-- <script src="{{ elixir('js/app.js') }}"></script> --}}
    
    @yield('footer')

</body>
</html>
