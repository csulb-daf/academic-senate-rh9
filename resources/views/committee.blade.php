@extends('layouts.app')

@section('title', 'Committee Management')

@section('content')
@if(session()->has('committee'))
	<div class="alert alert-success">
		{{ session()->get('committee') }}
	</div>
@endif			

<div id="validation-errors"></div>
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
			$('button.editButton', row).click(function() {
				$(this).closest('div.editButtons').hide();
				$(this).closest('div.editButtons').siblings('div.submitButtons').show();
				editRow(row);
			});
			$('button.cancelEdit', row).click(function() {
				$(this).closest('div.submitButtons').hide();
				$(this).closest('div.submitButtons').siblings('div.editButtons').show();
				cancelEdit(row);
			});
			$('button.submit', row).click(function() {
				submit(data.id, row, "{{ route('committee.update') }}");
			});
			$('button.deleteButton', row).click(function() {
				$(this).closest('div.editButtons').hide();
				$(this).closest('div.editButtons').siblings('div.deleteButtons').show();
			});
			$('button.cancelDelete', row).click(function() {
				$(this).closest('div.deleteButtons').hide();
				$(this).closest('div.deleteButtons').siblings('div.editButtons').show();
			});
			$('button.confirmDelete', row).click(function() {
				destroy(data.id, row, "{{ route('committee.destroy') }}");
			});
		},
    ajax: {
			url: "{{ route('committee.admin') }}",
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
			{ title: 'Committee Name', data: 'comm', width: '750px', className: 'editable', responsivePriority: 1,
				createdCell: function(td, cellData, rowData, row, col) {
					$(td).attr('data-name', 'commName');
				}
			},
			{ title: 'Charge Memberships', data: 'assignments' },
			{ title: 'Actions', data: null, defaultContent: '', width: '120px', responsivePriority: 2,
				render: function ( data, type, row ) {
					//console.log(data);
					var html = '\
						<div class="editButtons">\
							<button type="button" class="btn btn-light btn-sm border editButton">Edit</button>\n';
							if(data.assignments == 0) {
								html +='<button type="button" class="btn btn-danger btn-sm deleteButton">Delete</button>';
							}
							else {
								html += '<button type="button" class="btn btn-light btn-sm border" onclick="assignComm('+ data.id +')">Assign</button>';
							}
					html += '</div>\
						<div class="deleteButtons" style="display: none;">\
							<button type="button" class="btn btn-danger btn-sm confirmDelete">Confirm</button>\
							<button type="button" class="btn btn-light btn-sm border cancelDelete">Cancel</button>\
						</div>\
						<div class="submitButtons" style="display: none;">\
							<button type="button" class="btn btn-success btn-sm submit">Submit</button>\
							<button type="button" class="btn btn-light btn-sm border cancelEdit">Cancel</button>\
						</div>\
						<span class="badge badge-success saved" style="font-size: 14px; padding: 5px 10px; display: none;">Saved</span>\
					';
					return html;
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
	window.location = "{{ route('committee.add') }}";
}
function assignComm(id) {
	var url = 	"{{ route('comm.assign', ['id'=>':id']) }}";
	url = url.replace(':id', id);
	window.location = url;
}
</script>
@endsection