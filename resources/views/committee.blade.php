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
			$('button.editButton', row).click(function() {
				$(this).closest('div.editButtons').hide();
				$(this).closest('div.editButtons').siblings('div.confirmButtons').show();
				editRow(row);
			});
			$('button.cancelEdit', row).click(function() {
				$(this).closest('div.confirmButtons').hide();
				$(this).closest('div.confirmButtons').siblings('div.editButtons').show();
				cancelEdit(row);
			});

			$('button.cancelEdit', row).click(function() {
				$(this).closest('div.confirmButtons').hide();
				$(this).closest('div.confirmButtons').siblings('div.editButtons').show();
				cancelEdit(row);
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
			{ title: 'Committee Name', data: 'comm', width: '750px', className: 'editable', responsivePriority: 1},
			{ title: 'Charge Memberships', data: 'assignments' },
			{ title: 'Actions', data: null, defaultContent: '', width: '120px', responsivePriority: 2,
				render: function ( data, type, row ) {
					//console.log(data);
					var html = '\
						<div class="editButtons">\
							<button type="button" class="btn btn-light btn-sm border editButton">Edit</button>\n';
							if(data.assignments == 0) {
								html +='<button type="button" class="btn btn-light btn-sm border" onclick="javascrtipt:void(0);" disabled>Assign</button>';
							}
							else {
								html += '<button type="button" class="btn btn-light btn-sm border" onclick="assignComm('+ data.id +')">Assign</button>';
							}
						html += '</div>\
						<div class="confirmButtons" style="display: none;">\
							<button type="button" class="btn btn-light btn-sm border confirmUpdate"  onclick="updateComm('+ data.id +')">Confirm</button>\
							<button type="button" class="btn btn-light btn-sm border cancelEdit">Cancel</button>\
						</div>\
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
	window.location = "{{ route('committee.add', [], false) }}";
}
function assignComm(id) {
	var url = 	"{{ route('comm.assign', ['id'=>':id']) }}";
	url = url.replace(':id', id);
	window.location = url;
}
function editRow(row) {
	$('td.editable', row).each(function() {
		$(this).html('<input type="text" name="'+ $(this).data('name') +'" value="' + $(this).html() + '" style="width: 100%;" />');
	});
}
function cancelEdit(row) {
	$('td.editable', row).each(function() {
		if($(this).find('input').hasClass('error')) {
			$(this).html($(this).find('input').attr('value'));
		}
		else {
			$(this).html($(this).find('input').val());
		}
	});

	$('#validation-errors').html('').removeClass();
}

function updateComm(id) {
	var url = 	"{{ route('committee.update', [], false) }}";
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