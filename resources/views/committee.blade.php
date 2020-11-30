@push('head')
@endpush

@extends('layouts.app')

@section('title', 'Committee Management')

@section('content')
<table id="commAdmin" class="display"></table>
@endsection 

@section('scripts')
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
					test();
				}
			}
		],
	});
	
});

function test() {
	console.log('ok');
}
	
</script>
@endsection