@extends('layouts.app') 

@section('title', 'List Management')

@section('content') 

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

<form method="POST" id="chargeForm" action="{{ route('charge.add') }}">
	@csrf
	<input type="hidden" name="tabName" value="charge">
	
	<div class="input-group">
		<label for="chargeName" style="margin-top: 1em;">Charge Name:</label>
		<input class="form-control" type="text" name="chargeName" id="chargeName" value="{{ old('chargeName') }}" >
	</div>

	<div class="input-group">
		<label for="commSelect" style="margin-top: 1em;">Committee:</label>
		<select class="form-control" name="commSelect" id="commSelect">
			<option value="">Select Committee</option>
			<option value="0">Unassigned</option>
			
			@foreach ($comms as $comm)
				<option value="{{ $comm->id }}"  {{ old('commSelect') == $comm->id ? 'selected' : '' }}>{{ $comm->committeename }}</option>
			@endForeach
		</select>
	</div>

	<div class="input-group">
		<button class="btn btn-primary " type="submit">CREATE CHARGE MEMBERSHIP</button>
	</div>
	
</form>	

@endsection