@extends('layouts.app')

@section('title', 'Home')

@section('content')

<div id="selectContainer">
	@include('partials.committee-select')
</div>

<h2 style="font-weight: bold;">Committee: <span id="tableTitle"></span></h2>
<table id="commSearch" class="display"></table>
@endsection 

@section('scripts')
<script>
$(document).ready(function() {

	$('#commSelect').on('change', function() {
		$('#tableTitle').text($(this).find('option:selected').text());
		table.ajax.reload();
	});

	var table = $('#commSearch').DataTable({
		autoWidth: false,
		dom: 'Blfrtip',
		buttons: [{ 
			extend: 'pdf',
			text: 'Export to PDF', 
			className: 'btn btn-primary',
			title: function() {
				return $('#commSelect').find('option:selected').text();
			},
			orientation: 'landscape',
		}],		
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
			{ 
				title: 'Campus ID', 
				render: function(data, type, row, meta) {
					if(row.campus_id == null) {
						var cid = $('#commSelect').val();
						var url = 	"{{ route('members.add', ['id'=>':id']) }}";
						url = url.replace(':id', cid);
						return '<a href="'+ url +'">VACANT</a>';
					}
					return row.campus_id;
				}	
			},
			{ title: 'Last Name', data: 'lastname' },
			{ title: 'First Name', data: 'firstname' },
			{ title: 'Rank', data: 'rank' },
			{ title: 'Department', data: 'department' },
			{ title: 'College', data: 'college' },
			{ title: 'Ext.', data: 'ext' },
			{ title: 'Email', data: 'email' },
			{ title: 'Term', data: 'term' },
			{ title: 'Charge Memberhip', data: 'charge' },
			{ title: 'Alternate', data: null, defaultContent: '',
				render: function(data, type, row) {
					return data.alternate == 1? 'Y':'';
				}			
			},
			{ title: 'Notes', data: 'notes' },
		],
	});
});
</script>
@endsection