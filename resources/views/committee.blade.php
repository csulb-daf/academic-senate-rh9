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

	$('#committee').DataTable({
	dom: 'Bfrtip',
	buttons: [
            {
                text: 'Add new button',
                action: '',
                
						}
	],
    ajax: {
			url: 'committee-ajax',
			dataSrc: '',
    },
		columns: [
			{ title: 'Committee Name', data: 'committeename' },
		],
		
	});
	
	


});
</script>
@endpush