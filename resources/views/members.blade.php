@extends('layouts.app')

@section('title', 'Committee Management')

@section('content')
@if(session()->has('member'))
    <div class="alert alert-success">
        {{ session()->get('member') }}
    </div>
@endif			

<h2 class="tableTitle">{{ $cname }}</h2>
<table id="memberAdmin" class="display"></table>
@endsection 

@section('scripts')
<script>
$(document).ready(function() {
	$('div.container').addClass('wide');
	var url = 	"{{ route('members.table', ['cid'=>':cid']) }}";
	url = url.replace(':cid', {{ $cid }});
	
	var table = $('#memberAdmin').DataTable({
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

			if(data.alternate) {
				$(row).addClass('added');
			}
		},
    ajax: {
			url: url,
			dataSrc: '',
			error: function (xhr, error, thrown) {
				table.clear().draw();
			},
			complete: function() {}
    },
		columns: [
			{ title: 'Campus ID', defaultContent: '', responsivePriority: 1,
				render: function(data, type, row, meta) {
					if(row.campus_id == null) {
						return '<span class="badge badge-primary">Vacant</span>';
					}
					if(row.campus_id == 0) {
						return '<span class="badge badge-primary">CM</span>';
					}
					return row.campus_id;
				}	
			},
			{ title: 'Employee Type', data: 'emp_type' },
			{ title: 'Employee Sort', data: 'emp_sort', defaultContent: '200', visible: false },
			{ title: 'Last Name', data: 'lastname' },
			{ title: 'First Name', data: 'firstname' },
			{ title: 'Rank', data: 'rank' },
			{ title: 'Department', data: 'department' },
			{ title: 'College', data: 'college' },
			{ title: 'Ext.', data: 'ext' },
			{ title: 'Email', data: 'email' },
			{ title: 'Term', data: 'term' },
			{ title: 'Charge Memberhip', data: 'charge', width: '220px'},
			{ title: 'Alternate', data: null, defaultContent: '',
				render: function ( data, type, row ) {
					return data.alternate == 1? 'Y':'';
				}			
			},
			{ title: 'Notes', data: 'notes' },
			{ title: 'Actions', data: null, defaultContent: '', width: '120px', responsivePriority: 2,
				render: function(data, type, row, meta) {
					if(data.id == null) {
						var cid = {{ $cid }};
						var url = 	"{{ route('members.add.view', ['cid'=>':cid', 'mid'=>':mid', 'chid'=>':chid']) }}";
						url = url.replace(':cid', cid);
						url = url.replace(':mid', 0);
						url = url.replace(':chid', data.chargeID);
						return '<a href="'+ url +'" class="btn btn-light btn-sm border assignLink">Assign</button>';
					}
					var html='\
						<div class="editButtons">\
							<button type="button" class="btn btn-light btn-sm border editButton" onclick="editMember('+ data.id +')">Edit</button>\
							<button type="button" class="btn btn-danger btn-sm deleteButton">Vacate</button>\
						</div>\
						<div class="delButtons" style="display: none;">\
							<button type="button" class="btn btn-danger btn-sm confirmDelete"  onclick="deleteMember('+ data.id +')">Confirm</button>\
							<button type="button" class="btn btn-light btn-sm border cancelDelete">Cancel</button>\
						</div>\
					';
					return html;
				}			
			}
		],
		columnDefs: [{
			targets: [1, 8, 12, 13, 14],
			sortable: false,
		}],
		order: [[2, 'asc'], [0, 'asc']],
	});		//DataTable

	table.on('draw', function () {
		$('[data-toggle="tooltip"]').tooltip();
		$(this).find('tr.added:first').before('<tr id="altHeading" class="dtrg-group dtrg-start dtrg-level-0"><td colspan="14">Alternates</td></tr>');
	});
	
});

function editMember(id) {
	var url = 	"{{ route('members.edit', ['cid'=>':cid', 'mid'=>':mid']) }}";
	url = url.replace(':cid', {{ $cid }});
	url = url.replace(':mid', id);
	window.location = url;
}
function deleteMember(id) {
	var url = 	"{{ route('members.destroy', ['mid'=>':mid']) }}";
	url = url.replace(':mid', id);
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
			$('#memberAdmin').DataTable().ajax.reload();
		}
	});
}
</script>
@endsection