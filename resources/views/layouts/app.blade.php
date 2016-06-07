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
    <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700" rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="{{ elixir('css/all.css') }}">

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
                <ul class="nav navbar-nav navbar-right" id ="navbar">
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
                        <li><a href="{{ url('/tags') }}">Tags</a></li>
                    @endif
                        <li class="dropdown" id="drop">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li id="profile"><a href="/{{ Auth::user()->name }}/profile">My profile</a></li>
                                <li id="logout"><a href="{{ url('/logout') }}"><i class="fa fa-sign-out"></i> Logout</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
    <br>

    <div class="container">
      @yield('sides')
      <div class="center">
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
                                                    
                         {!! Form::button('<i class="fa fa-search"></i>', 
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
    <script src="{{ elixir('js/all.js') }}"></script>
        @if(Session::has('flash_message'))
            <script type="text/javascript">
                swal({ title: "Success!", text: "{{Session::get('flash_message')}}", timer: 2000, showConfirmButton: false, type:"success" });
            </script>
        @endif
    @yield('footer')

    <script type="text/javascript">
            $(window).bind("resize", function () {
               if($(window).width() < 753){
                    $('.right').hide();
                    $('.left').hide();
                    $('.center').css("width", "100%");
                    $('.bx-wrapper .bx-caption span').css("font-size", "3vw");
                    var profile = $('#profile').clone();
                    var logout  = $('#logout').clone();
                    $('#drop').hide();
                    if(!$('#navbar').children('#profile').length)
                    {
                        profile.appendTo('#navbar');
                        logout.appendTo('#navbar');
                    }
                } else {
                    $('.right').show();
                    $('.left').show();
                    $('.center').css("width", "53%");
                    $('.bx-wrapper .bx-caption span').css("font-size", "1.5vw");
                    $('#drop').show();
                    $('#navbar').children('#profile').remove();
                    $('#navbar').children('#logout').remove();
                }
            }).trigger('resize');
    </script>



</body>
</html>
