@extends('layouts.app')

@section('title', 'Home')

@section('content')
<form id="searchForm">
	<table id="commSearch" class="display"></table>
	<span id="searchContainter" style="display: none;">
		<select class="" name="commSelect"  id="commSelect" >
			<option value="option_select" disabled selected>Select Committee</option>
			@foreach($comms as $comm)
				<option value="{{ $comm->committeename }}">{{ $comm->committeename }}</option>
			@endforeach
		</select>
		<input type="checkbox" name="commVacancies" id="commVacancies" value=""><label for="commVacancies">Show Vacancies for committee</label>
	</span>
</form>
@endsection 

@section('scripts')
<script>
$(document).ready(function() {
	var table = $('#commSearch').DataTable({
    ajax: {
			url: 'comm-search',
			dataSrc: '',
			error: function(xhr, error, thrown) {
				table.clear().draw();
			},
			complete: function() {
				$("#searchContainter").appendTo("#commSearch_wrapper .dataTables_length").show();
			}	
    },
		columns: [
			{ title: 'Campus ID', data: 'user_id' },
			{ title: 'Last Name', data: 'lastname' },
			{ title: 'First Name', data: 'firstname' },
			{ title: 'Rank', data: 'rank' },
			{ title: 'Department', data: 'department' },
			{ title: 'College', data: 'college' },
			{ title: 'Ext.', data: 'ext' },
			{ title: 'Email', data: 'email' },
			{ title: 'Term', data: 'term' },
			{ title: 'Charge Memberhip', data: 'charge_memberhip' },
			{ title: 'Notes', data: 'notes' },
		],
		
	});	
			
});
</script>
@endsection