@extends('layouts.app')

@section('content')
<style>
          .table-padding td{
            padding: 3px 8px;
          }
</style>

<div class="container">
  @if (file_exists('pictures/'.$user->name))
    {{ Html::image(('pictures/'.$user->name)) }}
  @endif
  <h1>{{ $user->name }} </h1>
  <h5>{{ $user->email}} </h5>
  <div class="panel panel-default">
    <div class="panel-heading"> 
      Joined on {{$user->created_at->format('d M, Y \a\t H:i') }}.
    </div>
    <div class="panel-body">
      <table class="table-padding">
        
        <tr>
          <td>Published Articles:</td>
          <td> {{ $user->articles()->published()->count() }}</td>
          @unless($user->articles()->published()->count() == 0)
            <td><a href="/{{$user->name}}/articles">Show All</a></td>
          @endunless
        </tr>
        @if($user->id == Auth::id() || Auth::user()->isAdmin())
          <tr>
            <td>Unpublished Articles:</td>
            <td> {{ $user->articles()->unpublished()->count() }} </td>
            @unless($user->articles()->unpublished()->count() == 0)
              <td><a href="/{{$user->name}}/unpublished">Show All</a></td>
            @endunless
          </tr> 
        @endif
        <tr><td> Total Comments:</td> <td>{{ $user->comments()->count() }}</td></tr>
      </table>
    </div>
  </div>

 @unless($user->articles()->published()->count()==0)
 <div class="panel panel-default">
  <div class="panel-heading"><h4>Latest Article</h4></div>
  <div class="panel-body">
  
    <table class="table-padding">
      <tr>
        <td><a href="/articles/{{$user->articles()->latest('published_at')->published()->first()->slug}}">
        {{$user->articles()->latest('published_at')->published()->first()->title}}
        </a></td>
        <td>{{ $user->articles()->latest('published_at')->first()->published_at }}</td>
      </tr>
      <tr><td>{!! \Illuminate\Support\Str::words(html_entity_decode($user->articles()->latest('published_at')->published()->first()->body), 12) !!}</td></tr>
    </table>
 
  </div>
  </div>
 @endunless

 @unless($user->comments->isEmpty())
  <div class="panel panel-default">
  <div class="panel-heading"><h4>Latest Comment</h4></div>
  <div class="panel-body">

  <table class="table-padding">
      <tr>
        <td>"{{ str_limit($user->comments()->latest('created_at')->first()->body, 60) }}"</td>
        <td>{{ $user->comments()->latest('created_at')->first()->created_at->format('d M, Y \a\t H:i') }}.</td>
      </tr>
      <tr>
        <td>On article:</td>
        <td><a href="/articles/{{$user->comments()->latest('created_at')->first()->article->slug}}">
        {{ $user->comments()->latest('created_at')->first()->article->title }}</a></td>
      </tr>
    </table>
 
  </div>
  </div>
 @endunless

  <table><tr>
    @if($user->id == Auth::id())
      <td><a href="/{{$user->name}}/changepassword"><button class="btn btn-primary"><i class="fa fa-key"></i> Change Password </button></a></td>
    
      <td><a href="/{{$user->name}}/avatar"><button class="btn btn-primary"><i class="fa fa-file-image-o"></i>
          @if (file_exists('pictures/'.$user->name))
            Change Avatar
          @else
            Upload Avatar
          @endif
      </button></a></td>
    @endif
    @if($user->id == Auth::id() || Auth::user()->isAdmin())
      <td>
        {!!Form::open(['method' => 'DELETE', 'url' => $user->name.'/delete' ])!!}

          {!!Form::button('<i class="fa fa-trash"></i> Delete Profile', array('id' => 'delete', 'class' => 'btn btn-danger'))!!}

        {!!Form::close()!!}
      </td>
    @endif
  </tr></table>
  <hr>
</div>
@stop

@section('footer')

@include('ConfirmDelete')

@endsection
