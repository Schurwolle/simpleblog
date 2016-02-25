@extends('layouts.app')

@section('content')
<style>
          .table-padding td{
            padding: 3px 8px;
          }
</style>

<div class="container">
  <ul class="list-group">
    <h1> {{ $user->name }} </h1>
    <li class="list-group-item">
      Joined on {{$user->created_at->format('d M, Y \a\t H:i') }}.
    </li>
    <li class="list-group-item panel-body">
      <table class="table-padding">
        
        <tr>
          <td>Published Articles:</td>
          <td> {{ $user->articles()->published()->count() }}</td>
          @unless($user->articles()->published()->count() == 0)
            <td><a href="/{{$user->name}}/articles">Show All</a></td>
          @endunless
        </tr>
        @if($user->id == Auth::id())
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
    </li>
  </ul>

 @unless($user->articles()->published()->count()==0)
 <div class="panel panel-default">
  <div class="panel-heading"><h4>Latest Article</h4></div>
  <div class="panel-body">
  
    <table class="table-padding">
      <tr>
        <td><a href="/articles/{{$user->articles()->latest('published_at')->published()->first()->id}}">
        {{$user->articles()->latest('published_at')->published()->first()->title}}
        </a></td>
        <td>{{ $user->articles()->latest('published_at')->first()->published_at }}</td>
      </tr>
      <tr><td>{!! html_entity_decode(str_limit($user->articles()->latest('published_at')->published()->first()->body)) !!}</td></tr>
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
        <td>"{{ str_limit($user->comments()->latest('created_at')->first()->body) }}"</td>
        <td>{{ $user->comments()->latest('created_at')->first()->created_at->format('d M, Y \a\t H:i') }}.</td>
      </tr>
      <tr>
        <td>On article:</td>
        <td><a href="/articles/{{$user->comments()->latest('created_at')->first()->article->id}}">
        {{ $user->comments()->latest('created_at')->first()->article->title }}</a></td>
      </tr>
    </table>
 
  </div>
  </div>
 @endunless
</div>

 @if($user->id == Auth::id())
    <a href="/{{ $user->name }}/delete"><button class="btn btn-danger">
      <i class="fa fa-btn fa-trash"></i>Delete Profile
    </button></a>
 @endif


 @stop
