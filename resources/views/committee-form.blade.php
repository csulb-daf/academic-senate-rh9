@extends('layouts.app') 

@section('title', 'Committee Management')

@section('content') 
@if ($errors->any())
	<div class="alert alert-danger">
		<ul>
			@foreach ($errors->all() as $error)
				<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
@endif

<form method="POST" id="commForm" action="/committee/add">
	@csrf

	<div class="input-group">
		<label for="commName" style="margin-top: 1em;">Committee Name:</label>
		<input class="form-control" type="text" name="commName" id="commName" value="{{ old('commName') }}" >
	</div>

	<div class="input-group">
		<label for="meetTime" style="margin-top: 1em;">Meeting Time and Location:</label>
		<input class="form-control" type="text" name="meetTime" id="meetTime" value="{{ old('meetTime') }}" >
	</div>
	
	<div class="input-group">
		<label for="notes" style="margin-top: 1em;">Notes:</label>
		<textarea class="form-control" name="notes" id="notes" >{{ old('notes') }}</textarea>
	</div>

	<div class="form-group ">
		<button class="btn btn-primary mt-3" type="submit">Create Committee</button>
	</div>

</form>

@endsection 