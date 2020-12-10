@extends('layouts.app') 

@section('title', 'Committee Management')

@section('content') 

<div class="row">
	<div class="col-sm-4">
		@include('partials.directory-search')		
	</div>
		
	<div class="col">
		@if ($errors->any())
			<div class="alert alert-danger">
				<ul>
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif

		<form method="POST" id="memberForm" action="{{ route('members.add', ['cid' => $cid]) }}">
			@csrf
			<input type="hidden" name="cid" value="{{ $cid }}">
			
			<div class="input-group">
				<label for="fName" style="margin-top: 1em;">First Name:</label>
				<input class="form-control" type="text" name="fName" id="fName" value="{{ old('fName') }}" >
				
				<div class="form-check">
					<input type="checkbox" class="form-check-input" name="alternate" id="alternate" value="1" {{ old('alternate') == '1' ? 'checked' : '' }}>
					<label class="form-check-label" for="alternate">Alternate</label>
				</div>				
			</div>
		
			<div class="input-group">
				<label for="lName" style="margin-top: 1em;">Last Name:</label>
				<input class="form-control" type="text" name="lName" id="lName" value="{{ old('lName') }}" >
			</div>
			
			<div class="input-group">
				<label for="campusID" style="margin-top: 1em;">Campus ID:</label>
				<input class="form-control" type="text" name="campusID" id="campusID" value="{{ old('campusID') }}" >
			</div>
			
			<div class="input-group">
				<label for="termSelect" style="margin-top: 1em;">Term:</label>
				<select class="form-control" name="termSelect" id="termSelect">
					<option value="">Select</option>
					
					@for ($year = date('Y'); $year <= date('Y') + 4; $year++)
						<option value="{{$year}}" {{ old('termSelect') == $year ? 'selected' : '' }}>{{$year}}</option>
					@endfor
					
					<option value="Ex-Officio" {{ old('termSelect') === 'Ex-Officio' ? 'selected' : '' }}>Ex-Officio</option>
				</select>
			</div>
			
			<div class="input-group">
				<label for="chargeSelect" style="margin-top: 1em;">Charge Membership:</label>
				<select class="form-control" name="chargeSelect" id="chargeSelect">
					<option value="">Select</option>
					
					@foreach ($charges as $charge)
						<option value="{{ $charge->id }}" {{ old('chargeSelect') == $charge->id ? 'selected' : '' }} >{{ $charge->charge_membership }}</option>
					@endForeach
				</select>
			</div>
		
			<div class="input-group">
				<label for="rankSelect" style="margin-top: 1em;">Rank:</label>
				<select class="form-control" name="rankSelect" id="rankSelect">
					<option value="">Select</option>
					
					@foreach ($ranks as $rank)
						<option value="{{ $rank->id }}" {{ old('rankSelect') == $rank->id ? 'selected' : '' }}>{{ $rank->rank }}</option>
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
	</div> {{-- col --}}
</div>	{{-- row --}}
@endsection