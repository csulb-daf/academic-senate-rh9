@extends('layouts.app')

@section('title', 'Home')

@section('content')
<select class="commSelect form-control" style="margin: 20px 0;" name="commSelect"  id="commSelect">
	<option value="" disabled selected>Select Committee</option>
	@foreach($comms as $comm)
		<option value="{{ $comm->id }}">{{ $comm->committeename }}</option>
	@endforeach
</select>
	
<h2 style="font-weight: bold;">Committee: <span id="tableTitle"></span></h2>
<table id="commSearch" class="display"></table>
@endsection 

@section('scripts')
<script>
$(document).ready(function() {
	var cid = null;

	$('#commSelect').on('change', function() {
		$('#tableTitle').text($(this).find('option:selected').text());
		table.ajax.reload();
	});

	var table = $('#commSearch').DataTable({
		dom: 'Blfrtip',
		buttons: [
			{ extend: 'pdf', text: 'Export to PDF', className: 'btn btn-primary glyphicon glyphicon-file' },		
		],		

		ajax: {
			url: 'comm-search',
			data: function(d) {
				d.cid = $('#commSelect').val();
			},
			dataSrc: '',
			error: function(xhr, error, thrown) {
				table.clear().draw();
			},
			complete: function() {}	
    },
		columns: [
			{ title: 'Campus ID', data: 'campus_id', defaultContent: '<a href="#">VACANT</a>' },
			{ title: 'Committee', data: 'committee' },
			{ title: 'Last Name', data: 'lastname' },
			{ title: 'First Name', data: 'firstname' },
			{ title: 'Rank', data: 'rank' },
			{ title: 'Department', data: 'department' },
			{ title: 'College', data: 'college' },
			{ title: 'Ext.', data: 'ext' },
			{ title: 'Email', data: 'email' },
			{ title: 'Term', data: 'term' },
			{ title: 'Charge Memberhip', data: 'charge' },
			{ title: 'Alternate', data: 'alternate' },
			{ title: 'Notes', data: 'notes' },
		],
	});
});
</script>
@endsection