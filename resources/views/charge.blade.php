@extends('layouts.app')

@section('title', 'Committee Management')

@section('content')
<table id="chargeAdmin" class="display"></table>
@endsection 

@section('scripts')
<script>
$(document).ready(function() {
	var table = $('#chargeAdmin').DataTable({
		responsive: true,
		autoWidth: false,
    ajax: {
			url: "{{ route('charge.admin') }}",
			dataSrc: '',
			error: function (xhr, error, thrown) {
				table.clear().draw();
			},
			complete: function() {}
    },
		columns: [
			{ title: 'Committee Name', data: 'comm', responsivePriority: 1},
			{ title: 'Charge Memberships', data: 'assignments' },
			{ title: 'Actions', data: null, defaultContent: '', responsivePriority: 2,
				render: function ( data, type, row ) {
					//console.log('id', data.id);
    			return '<button type="button" class="btn btn-light btn-sm border" onclick="javascrtipt:assignCharge('+ data.id +')">Edit</button>';
				}			
			}
		],
		columnDefs: [{
			targets:  [1, 2],
			sortable: false,
		}],
	});		
});

function assignCharge(id) {
	var url = 	"{{ route('charge.assignments', ['id'=>':id']) }}";
	url = url.replace(':id', id);
	window.location = url;
}
</script>
@endsection