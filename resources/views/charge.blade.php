@extends('layouts.app')

@section('title', 'Committee Management')

@section('content')
<table id="chargeAdmin" class="display"></table>
{{-- <button type="button" class="btn btn-primary" id="addChargeMem" style="display: none; float: left;"  onclick="javascript:addChargeMem();">Add New Charge Membership</button> --}}
@endsection 

@section('scripts')
<script>
$(document).ready(function() {
	var table = $('#chargeAdmin').DataTable({
    ajax: {
			url: "{{ route('charge.admin') }}",
			dataSrc: '',
			error: function (xhr, error, thrown) {
				table.clear().draw();
			},
			
			complete: function() {
// 				$('.dataTables_length').css({
// 					'float' : 'right',
// 					'margin-left' : '30px'
// 				});
// 				$("button#addChargeMem").prependTo("#chargeAdmin_wrapper").show();
			}
    },
		columns: [
			{ title: 'Committee Name', data: 'comm' },
			
			{ title: 'Assignments', data: null, defaultContent: '',
				render: function ( data, type, row ) {
					//console.log('id', data.id);
    			return '<button type="button" class="btn btn-light border" onclick="javascrtipt:assignCharge('+ data.id +')">Edit</button>';
				}			
			}
		],
		
		columnDefs: [{		//Assignments column
			targets:  1,
			sortable: false,
		}],
		
	});		
	
});

// function addChargeMem() {
// 	window.location = "{{ url('/charge/add') }}";
// }

function assignCharge(id) {
	var url = 	"{{ route('charge.assignments', ['id'=>':id']) }}";
	url = url.replace(':id', id);
	window.location = url;
}
</script>
@endsection