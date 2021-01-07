@extends('layouts.app')

@section('title', 'Committee Management')

@section('content')
@if(session()->has('member'))
    <div class="alert alert-success">
        {{ session()->get('member') }}
    </div>
@endif			

{{-- <button type="button" class="btn btn-primary" id="addMember" style="margin-bottom: 20px;"  onclick="javascript:addMember({{ $cid }});">Add New Committee Member</button> --}}
<h2 style="font-weight: bold;">{{ $cname }}</h2>
<table id="memberAdmin" class="display" style="width: 100%"></table>
@endsection 

@section('scripts')
<script>
$(document).ready(function() {
	var table = $('#memberAdmin').DataTable({
		autoWidth: false,
		createdRow: function(row, data, dataIndex) {
			//setEdit(row, communityTable, "{{ route('community.update', [], false) }}", "{{ route('community.destroy', [], false) }}");
		},
    ajax: {
			url: '/committee/members/{{ $cid }}/ajax',
			dataSrc: '',
			error: function (xhr, error, thrown) {
				table.clear().draw();
			},
			complete: function() {
// 				$('.dataTables_length').css({
// 					'float' : 'right',
// 					'margin-left' : '30px'
// 				});
// 				$("button#addMember").prependTo("#memberAdmin_wrapper").show();
			}
    },
		columns: [
			{ title: 'Campus ID',
				render: function(data, type, row, meta) {
					if(row.campus_id == null) {
						var cid = {{ $cid }};
						var url = 	"{{ route('members.add', ['id'=>':id'], false) }}";
						url = url.replace(':id', cid);
						return '<a href="'+ url +'" data-id="">VACANT</a>';
					}
					if(row.campus_id == 0) {
						return '<span class="badge badge-primary communityTag" style="color: #fff; font-size: 16px; margin-top: 10px;">CM</span>';
					}
					return row.campus_id;
				}	
			},
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
			{ title: 'Actions', data: null, defaultContent: '', width: '120px',
				render: function(data, type, row, meta) {
					//console.log('data', data);
					if(data.id == null) {
						return null;
					}
					return getEditButtons(data.id);
				}			
			}
		],
		columnDefs: [{
			targets:  12,
			sortable: false,
		}],
	});		
});

function addMember(id) {
	var url = 	"{{ route('members.add', ['cid'=>':id']) }}";
	url = url.replace(':id', id);
	window.location = url;
}
function getEditButtons(id) {
	var html='\
		<div class="editButtons">\
				<button type="button" class="btn btn-light btn-sm editButton">Edit</button>\
				<button type="button" class="btn btn-danger btn-sm deleteButton">Vacate</button>\
				<img src="/images/check.svg" class="saved" style="width: 35px; display: none;">\
			</div>\
			<div class="delButtons" style="display: none;">\
					<button type="button" class="btn btn-danger btn-sm confirmDelete" data-id="'+ id +'">Confirm</button>\
					<button type="button" class="btn btn-light btn-sm cancelDelete">Cancel</button>\
				</div>\
		';
		return html;
}
	
</script>
@endsection