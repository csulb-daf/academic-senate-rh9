@extends('layouts.app') 

@section('title', 'List Management')

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

<form method="POST" id="communityForm" action="{{ route('community.add') }}">
	@csrf

	<div class="input-group">
		<label for="fName" style="margin-top: 1em;">First Name:</label>
		<input class="form-control" type="text" name="fName" id="fName" value="{{ old('fName') }}" >
	</div>

	<div class="input-group">
		<label for="lName" style="margin-top: 1em;">Lat Name:</label>
		<input class="form-control" type="text" name="lName" id="lName" value="{{ old('lName') }}" >
	</div>
	
	<div class="input-group">
		<label for="campusID" style="margin-top: 1em;">Email:</label>
		<input class="form-control" type="text" name="email" id="email" value="{{ old('email') }}" >
	</div>
	
	<div class="input-group">
		<label for="chargeSelect" style="margin-top: 1em;">Charge Membership:</label>
		<select class="form-control" name="chargeSelect" id="chargeSelect">
			<option value="">Select</option>
			
			@foreach ($charges as $charge)
				<option value="{{ $charge->id }}" {{ old('chargeSelect') == $charge->id ? 'selected' : '' }}>{{ $charge->charge_membership }}</option>
			@endForeach
		</select>
	</div>

	<div class="input-group">
		<label for="commSelect" style="margin-top: 1em;">Committee:</label>
		<select class="form-control" name="commSelect" id="commSelect">
			<option value="">Select</option>
			
			@foreach ($comms as $comm)
				<option value="{{ $comm->id }}"  {{ old('commSelect') == $comm->id ? 'selected' : '' }}>{{ $comm->committeename }}</option>
			@endForeach
		</select>
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