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

<form method="POST" id="memberForm" action="{{ route('members.add') }}">
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
		<label for="campusID" style="margin-top: 1em;">Campus ID:</label>
		<input class="form-control" type="text" name="campusID" id="campusID" value="{{ old('campusID') }}" >
	</div>
	
	<div class="input-group">
		<label for="term" style="margin-top: 1em;">Term:</label>
		<select class="form-control" name="term" id="term">
			<option>Test</option>
		</select>
	</div>
	
	<div class="input-group">
		<label for="charge" style="margin-top: 1em;">Charge Membership:</label>
		<select class="form-control" name="charge" id="charge">
			<option>Test</option>
		</select>
	</div>

	<div class="input-group">
		<label for="rank" style="margin-top: 1em;">Rank:</label>
		<select class="form-control" name="rank" id="rank">
			<option>Test</option>
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

@endsection @section('scripts')
<script>
$(document).ready(function() {
	var table = $('#commAdmin').DataTable({
    ajax: {
			url: 'comm-admin',
			dataSrc: '',
			error: function (xhr, error, thrown) {
				table.clear().draw();
			},
    },
		columns: [
			{ title: 'Committee Name', data: 'committeename' },
			{ title: 'Assignments', data: null, defaultContent: '',
				render: function ( data, type, row ) {
    			return '<button>Edit</button>';
				}			
			}
		],
		
		dom: 'Blfrtip',
		buttons: [
			{
				text: 'Add New Committee',
				action: function ( e, dt, node, config ) {
					addComm();
				}
			}
		],
	});
	
});

function addComm() {
	//console.log('ok');
	window.location = "{{ url('/committee/add') }}";
}
	
</script>
@endsection