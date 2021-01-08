@extends('layouts.app') 

@section('title', 'Committee Management')

@section('content') 

		<h2 style="font-weight: bold; text-align: center; margin-bottom: 20px;">{{ $cname }}</h2>
<div class="row">
	@if(empty($mid))
	<div class="col-sm-4">
		@include('partials.directory-search')		
	</div>
	@endif
	
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

		@if(empty($mid))
			@php $route =  route('members.add', ['cid' => $cid]) @endphp
		@else
			@php $route = route('members.update', ['mid' => $mid]) @endphp
		@endif
		
		<form method="POST" id="memberForm" action="{{ $route }}">
			@csrf
			<input type="hidden" name="cid" value="{{ $cid }}">
			<input type="hidden" name="mid" value="{{ isset($mid)? $mid:'' }}">
			
			<div class="input-group">
				<label for="fName" style="margin-top: 1em;">First Name:</label>
				<input class="form-control" type="text" name="fName" id="fName" value="{{ old('fName', isset($fname)? $fname:'') }}" >
				
				<div class="form-check">
					<input type="checkbox" class="form-check-input" name="alternate" id="alternate" value="1" {{ old('alternate', isset($alternate)? $alternate:'') == '1' ? 'checked' : '' }}>
					<label class="form-check-label" for="alternate">Alternate</label>
				</div>				
			</div>
		
			<div class="input-group">
				<label for="lName" style="margin-top: 1em;">Last Name:</label>
				<input class="form-control" type="text" name="lName" id="lName" value="{{ old('lName', isset($lname)? $lname:'') }}" >
			</div>
			
			<div class="input-group">
				<label for="campusID" style="margin-top: 1em;">Campus ID:</label>
				<input class="form-control" type="text" name="campusID" id="campusID" value="{{ old('campusID', isset($campusID)? $campusID:'') }}">
			</div>
			
			<div class="input-group">
				<label for="termSelect" style="margin-top: 1em;">Term:</label>
				<select class="form-control" name="termSelect" id="termSelect">
					<option value="">Select</option>
					
					@for ($year = date('Y'); $year <= date('Y') + 4; $year++)
						<option value="{{$year}}" {{ old('termSelect', isset($termID)? $termID:'') == $year ? 'selected' : '' }}>{{$year}}</option>
					@endfor
					
					<option value="Ex-Officio" {{ old('termSelect', isset($term)? $term:'') === 'Ex-Officio' ? 'selected' : '' }}>Ex-Officio</option>
				</select>
			</div>
			
			<div class="input-group">
				<label for="chargeSelect" style="margin-top: 1em;">Charge Membership:</label>
				<select class="form-control" name="chargeSelect" id="chargeSelect">
					<option value="">Select</option>
					
					@if(isset($chargeID))
						<option value="{{ $chargeID }}" selected>{{ $chargeName }}</option>
					@endif
					
					@foreach ($charges as $charge)
						<option value="{{ $charge->id }}" {{ old('chargeSelect') == $charge->id ? 'selected' : '' }} >{{ $charge->charge }}</option>
					@endForeach
				</select>
			</div>
		
			<div class="input-group">
				<label for="rankSelect" style="margin-top: 1em;">Rank:</label>
				<select class="form-control" name="rankSelect" id="rankSelect">
					<option value="">Select</option>
					
					@foreach ($ranks as $rank)
						<option value="{{ $rank->id }}" {{ old('rankSelect', isset($rankID)? $rankID:'') == $rank->id ? 'selected' : '' }}>{{ $rank->rank }}</option>
					@endForeach
				</select>
			</div>
			
			<div class="input-group">
				<label for="notes" style="margin-top: 1em;">Notes:</label>
				<textarea class="form-control" name="notes" id="notes" >{{ old('notes', isset($notes)? $notes:'') }}</textarea>
			</div>
		
			<div class="form-group ">
				<button class="btn btn-primary mt-3" type="submit">{{ empty($mid)? 'Assign to Committee':'Update' }}</button>
			</div>
		</form>
	</div> {{-- col --}}
</div>	{{-- row --}}
@endsection

@section('scripts')
<script>
$(document).ready(function() {
	$('select#userSelect').change(function() {
		var nameArr = $('select#userSelect option:selected').attr('data-name').split(',');
		var campusID = $('select#userSelect option:selected').val();
		var lastName = nameArr[0].trim();
		var firstName = nameArr[1].trim();
		
		var form = $('#memberForm');
		form.find('#fName').val(firstName);
		form.find('#lName').val(lastName);
		form.find('#campusID').val(campusID);

		if(campusID == 0) {
			form.find('#campusID').prop('readonly', true);
		}
	});
});
</script>
@endsection