@extends('layouts.app') 

@section('title', 'Committee Management')

@section('content') 

<h2 class="tableTitle form">{{ $cname }}</h2>
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
			<input type="hidden" name="mid" value="{{ !empty($mid)? $mid:'' }}">
			
			<div class="form-group row">
				<label for="fName" class="col-sm-2 col-form-label">First Name:</label>
				<div class="col">
					<input class="form-control" type="text" name="fName" id="fName" value="{{ old('fName', isset($fname)? $fname:'') }}" readonly>
					
					<div class="form-check" style="margin-top: 10px;">
						<input type="checkbox" class="form-check-input" name="alternate" id="alternate" value="1" {{ old('alternate', isset($alternate)? $alternate:'') == '1' ? 'checked' : '' }}>
						<label class="form-check-label" for="alternate">Alternate</label>
					</div>
				</div>
			</div>
		
			<div class="form-group row">
				<label for="lName" class="col-sm-2 col-form-label">Last Name:</label>
				<div class="col-sm-10">
					<input class="form-control" type="text" name="lName" id="lName" value="{{ old('lName', isset($lname)? $lname:'') }}" readonly>
				</div>
			</div>
			
			<div class="form-group row">
				<label for="campusID" class="col-sm-2 col-form-label">Campus ID:</label>
				<div class="col-sm-10">
					<input class="form-control" type="text" name="campusID" id="campusID" value="{{ old('campusID', isset($campusID)? $campusID:'') }}" readonly>
				</div>
			</div>
			
			<div class="form-group row">
				<label for="department" class="col-sm-2 col-form-label">Department:</label>
				<div class="col-sm-10">
					<input class="form-control" type="text" name="department" id="department" value="{{ old('department', isset($department)? $department:'') }}" readonly>
				</div>
			</div>
			
			<div class="form-group row">
				<label for="collegeDepartment" class="col-sm-2 col-form-label">College Department:</label>
				<div class="col-sm-10">
					<input class="form-control" type="text" name="college_department" id="collegeDepartment" value="{{ old('college_department', isset($college_department)? $college_department:'') }}" readonly>
				</div>
			</div>
			
			<div class="form-group row">
				<label for="extension" class="col-sm-2 col-form-label">Extension:</label>
				<div class="col-sm-10">
					<input class="form-control" type="text" name="extension" id="extension" value="{{ old('extension', isset($extension)? $extension:'') }}" readonly>
				</div>
			</div>
			
			<div class="form-group row">
				<label for="email" class="col-sm-2 col-form-label">Email:</label>
				<div class="col-sm-10">
					<input class="form-control" type="text" name="email" id="email" value="{{ old('email', isset($email)? $email:'') }}" readonly>
				</div>
			</div>
			
			<div class="form-group row">
				<label for="termSelect" class="col-sm-2 col-form-label">Term:</label>
				<div class="col-sm-10">
					<select class="form-control" name="termSelect" id="termSelect" required>
						<option value="">Select</option>
						
						@for ($year = date('Y'); $year <= date('Y') + 4; $year++)
							<option value="{{$year}}" {{ old('termSelect', isset($termID)? $termID:'') == $year ? 'selected' : '' }}>{{$year}}</option>
						@endfor
						
						<option value="Ex-Officio" {{ old('termSelect', isset($term)? $term:'') === 'Ex-Officio' ? 'selected' : '' }}>Ex-Officio</option>
					</select>
					</div>
			</div>
			
			<div class="form-group row">
				<label for="chargeSelect" class="col-sm-2 col-form-label">Charge Membership:</label>
				<div class="col-sm-10">
					<select class="form-control" name="chargeSelect" id="chargeSelect" required>
						<option value="">Select</option>
						
						@if(isset($chargeID))
							<option value="{{ $chargeID }}" selected>{{ $chargeName }}</option>
						@endif
						
						@foreach ($charges as $charge)
							<option value="{{ $charge->id }}" {{ old('chargeSelect') == $charge->id ? 'selected' : '' }} >{{ $charge->charge }}</option>
						@endForeach
					</select>
				</div>
			</div>
		
			<div class="form-group row">
				<label for="rankSelect" class="col-sm-2 col-form-label">Rank:</label>
				<div class="col-sm-10">
					<select class="form-control" name="rankSelect" id="rankSelect" required>
						<option value="">Select</option>
						
						@foreach ($ranks as $rank)
							<option value="{{ $rank->id }}" {{ old('rankSelect', isset($rankID)? $rankID:'') == $rank->id ? 'selected' : '' }}>{{ $rank->rank }}</option>
						@endForeach
					</select>
				</div>
			</div>
			
			<div class="form-group row">
				<label for="notes" class="col-sm-2 col-form-label">Notes:</label>
				<div class="col-sm-10">
					<textarea class="form-control" name="notes" id="notes" >{{ old('notes', isset($notes)? $notes:'') }}</textarea>
				</div>
			</div>
		
			<div class="form-group">
				<button class="btn btn-primary mt-3" type="submit" id="submitButton" {{ !empty($mid)? '':'disabled' }}>{{ empty($mid)? 'Assign to Committee':'Update' }}</button>
			</div>
		</form>
	</div> {{-- col --}}
</div>	{{-- row --}}
@endsection

@section('scripts')
<script>
$(document).ready(function() {
	$('select#userSelect').on('select2:select', function(e) {
// 		console.log(e.params.data);
		var nameArr = e.params.data.text.split(','),
		lastName = nameArr[0].trim(),
		firstName = nameArr[1].replace('(CM)', '').trim(),
		campusID = e.params.data.id,
		department = e.params.data.department,
		college_department = e.params.data.college_department,
		extension = e.params.data.extension,
		email = e.params.data.email,
		form = $('#memberForm');
		
		form.find('#fName').val(firstName);
		form.find('#lName').val(lastName);
		form.find('#campusID').val(campusID);
		form.find('#department').val(department);
		form.find('#collegeDepartment').val(college_department);
		form.find('#extension').val(extension);
		form.find('#email').val(email);

		$('button#submitButton').prop('disabled', false);
	});
});
</script>
@endsection