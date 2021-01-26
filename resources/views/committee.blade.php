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
		createdRow: function(row, data, dataIndex) {
			$('button.deleteButton', row).click(function() {
				$(this).closest('div.editButtons').hide();
				$(this).closest('div.editButtons').siblings('div.delButtons').show();
			});
			$('button.cancelDelete', row).click(function() {
				$(this).closest('div.delButtons').hide();
				$(this).closest('div.delButtons').siblings('div.editButtons').show();
			});
		},
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
			{ title: 'Committee Name', data: 'comm', width: '750px', responsivePriority: 1},
			{ title: 'Charge Memberships', data: 'assignments' },
			{ title: 'Actions', data: null, defaultContent: '', width: '120px', responsivePriority: 2,
				render: function ( data, type, row ) {
					//console.log(data);
					if(data.assignments == 0) {
						var html = '\
							<div class="editButtons">\
								<button type="button" class="btn btn-danger btn-sm deleteButton">Delete</button>\
							</div>\
							<div class="delButtons" style="display: none;">\
								<button type="button" class="btn btn-danger btn-sm confirmDelete"  onclick="deleteComm('+ data.id +')">Confirm</button>\
								<button type="button" class="btn btn-light btn-sm border cancelDelete">Cancel</button>\
							</div>\
						';
						return html;
					}
						
    			return '<button type="button" class="btn btn-light btn-sm border" onclick="javascrtipt:assignComm('+ data.id +')">Edit</button>';
				}			
			}
		],
		columnDefs: [{		//Assignments column
			targets:  [1, 2],
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
function deleteComm(id, row) {
	var url = 	"{{ route('committee.destroy', [], false) }}";
	$.ajax({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
		type: 'post',
		url: url,
		data: {
			id: id,
		},
		success: function(data) {
			$('#commAdmin').DataTable().ajax.reload();
		}
	});
}
</script>
@endsection