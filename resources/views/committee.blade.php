@extends('layouts.app')

@section('title', 'Committee Management')

@section('content')
<table id="commAdmin" class="display"></table>
<button type="button" class="btn btn-primary" id="addComm" style="display: none; float: left;"  onclick="javascript:addComm();">Add New Committee</button>
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
			
			complete: function() {
				$('.dataTables_length').css({
					'float' : 'right',
					'margin-left' : '30px'
				});
				$("button#addComm").prependTo("#commAdmin_wrapper").show();
			}
    },
		columns: [
			{ title: 'Committee Name', data: 'committeename' },
			{ title: 'Assignments', data: null, defaultContent: '',
				render: function ( data, type, row ) {
    			return '<button type="button" class="btn btn-light border" id="" onclick="javascrtipt:assignComm()">Edit</button>';
				}			
			}
		],
		
		columnDefs: [{		//Assignments column
			targets:  1,
			sortable: false,
		}],
		
	});		
	
});

function addComm() {
	window.location = "{{ url('/committee/add') }}";
}
function assignComm() {
// 	window.location = "{{ url('/committee/assign') }}";
	window.location = "{{ route('comm.assign', ['id'=>1]) }}";
}

	
</script>
@endsection