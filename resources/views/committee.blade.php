@push('head')
@endpush

@extends('layouts.app')

@section('title', 'Committee Management')

@section('content')
<table id="committee" class="display"></table>
@endsection 

@push('scripts')

<script>
$(document).ready(function() {

	var table = $('#committee').DataTable({
    ajax: {
			url: 'committee-ajax',
			dataSrc: '',
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
@endpush