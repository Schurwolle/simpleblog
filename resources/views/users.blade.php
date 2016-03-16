@extends('layouts.app')

@section('content')

	<h1>Registered Users</h1>
	<hr>
	<table>
	@if($users->count() > 0)
		@foreach ($users as $user)
		<tr><td>
			<h2><a href="/{{ $user->name }}/profile">{{ $user->name }}</a></h2>
		</td></tr>
		<tr><td>Joined on {{$user->created_at->format('d M, Y \a\t H:i') }}.</td></tr>
		@endforeach
	@else
		<h3>There are no registered users at the moment.</h3>
	@endif
	</table>
	<hr>
	{!! $users->links() !!}

@stop