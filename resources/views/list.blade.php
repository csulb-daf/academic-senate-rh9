@push('head')
@endpush

@extends('layouts.app')

@section('title', 'List Management')

@section('content')
<table id="listAdmin" class="display"></table>
<form id="addForm">
	<button>ADD</button>
	<input type="text">
</form>
@endsection 

@section('scripts')
<script>
$(document).ready(function() {
	var table = $('#listAdmin').DataTable({
    ajax: {
			url: 'list-admin',
			dataSrc: '',
			error: function (xhr, error, thrown) {
				table.clear().draw();
			},
			complete: function() {
				
			}	
    },
		columns: [
			{ title: '#', data: null },
			{ title: 'Charge Membership', data: 'charge_membership' },
			{ title: 'Actions', data: null, defaultContent: '',
				render: function ( data, type, row ) {
    			return '<button>Edit</button>';
				}			
			}
		],
		columnDefs: [{		//index column
			sortable: false,
			"class": "index",
			targets: 0
		}],
		order: [[ 1, 'asc' ]],
		
	});
	
	table.on( 'order.dt search.dt', function() {		//index column
		table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
			cell.innerHTML = i+1;
		});
	}).draw();
});

function test() {
	console.log('ok');
}
	
</script>
@endsection