@extends('layouts.app')

@section('title', 'Committee Management')

@section('content')
@if(session()->has('committee'))
	<div class="alert alert-success">
		{{ session()->get('committee') }}
	</div>
@endif			

<table id="commAdmin" class="display"></table>
<button type="button" class="btn btn-primary" id="addComm" style="display: none; float: left;"  onclick="javascript:addComm();">Add New Committee</button>
@endsection 

@section('scripts')
<script>
$(document).ready(function() {
	var table = $('#commAdmin').DataTable({
		responsive: true,
		autoWidth: false,
    ajax: {
			url: "{{ route('committee.admin', [], false) }}",
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
			{ title: 'Committee Name', data: 'comm' },
			{ title: 'Charge Memberships', data: 'assignments' },
			{ title: 'Actions', data: null, defaultContent: '',
				render: function ( data, type, row ) {
					//console.log(data);
					if(data.assignments == 0) {
						return '<button type="button" class="btn btn-light border" onclick="javascrtipt:void(0);" disabled>Edit</button>';
					}
						
    			return '<button type="button" class="btn btn-light border" onclick="javascrtipt:assignComm('+ data.id +')">Edit</button>';
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

function assignComm(id) {
	var url = 	"{{ route('comm.assign', ['id'=>':id']) }}";
	url = url.replace(':id', id);
	window.location = url;
}
</script>
@endsection