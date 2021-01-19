@extends('layouts.app') 

@section('title', 'List Management')

@section('content') 
<h2 class="tableTitle form">LIST MANAGMENT : COMMUNITY MEMBERS</h2>
@if ($errors->any())
	<div class="alert alert-danger">
		<ul>
			@foreach ($errors->all() as $error)
				<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
@endif

<form method="POST" id="communityForm" action="{{ route('community.add') }}">
	@csrf
	<input type="hidden" name="tabName" value="community">

	<div class="form-group row">
		<label for="fName" class="col-sm-2 col-form-label">First Name:</label>
		<div class="col">
			<input class="form-control" type="text" name="fName" id="fName" value="{{ old('fName') }}" required>
		</div>
	</div>

	<div class="form-group row">
		<label for="lName" class="col-sm-2 col-form-label">Last Name:</label>
		<div class="col">
			<input class="form-control" type="text" name="lName" id="lName" value="{{ old('lName') }}" required>
		</div>
	</div>
	
	<div class="form-group row">
		<label for="campusID" class="col-sm-2 col-form-label">Email:</label>
		<div class="col">
			<input class="form-control" type="text" name="email" id="email" value="{{ old('email') }}" required>
		</div>
	</div>
	
	<div class="form-group row">
		<label for="notes" class="col-sm-2 col-form-label">Notes:</label>
		<div class="col">
			<textarea class="form-control" name="notes" id="notes" >{{ old('notes') }}</textarea>
		</div>
	</div>

	<div class="form-group">
		<button class="btn btn-primary mt-3" type="submit">Create Community Member</button>
	</div>

</form>

@endsection