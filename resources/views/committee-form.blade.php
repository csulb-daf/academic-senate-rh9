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

	<div class="form-group row">
		<label for="commName" class="col-sm-2 col-form-label">Committee Name:</label>
		<div class="col-sm-10">
			<input class="form-control" type="text" name="commName" id="commName" value="{{ old('commName') }}" >
		</div>
	</div>

	<div class="form-group row">
		<label for="meetTime" class="col-sm-2 col-form-label">Meeting Time and Location:</label>
		<div class="col-sm-10">
			<input class="form-control" type="text" name="meetTime" id="meetTime" value="{{ old('meetTime') }}" >
		</div>
	</div>
	
	<div class="form-group row">
		<label for="notes" class="col-sm-2 col-form-label">Notes:</label>
		<div class="col-sm-10">
			<textarea class="form-control" name="notes" id="notes" >{{ old('notes') }}</textarea>
		</div>
	</div>

	<div class="form-group">
		<button class="btn btn-primary mt-3" type="submit">Create Committee</button>
	</div>

</form>

@endsection 