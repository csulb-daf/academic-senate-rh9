@extends('layouts.app')

@section('title', 'Committee Management')

@section('content')
@if(session()->has('member'))
    <div class="alert alert-success">
        {{ session()->get('member') }}
    </div>
@endif			

<button type="button" class="btn btn-primary" id="addMember" style="margin-bottom: 20px;"  onclick="javascript:addMember({{ $cid }});">Add New Committee Member</button>
<h2 style="font-weight: bold;">{{ $cname }}</h2>
<table id="memberAdmin" class="display"></table>
@endsection 

@section('scripts')
<script>
$(document).ready(function() {
	var table = $('#memberAdmin').DataTable({
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
			{ title: 'Campus ID', data: 'campus_id' },
			{ title: 'Committee', data: 'committee' },
			{ title: 'Last Name', data: 'lastname' },
			{ title: 'First Name', data: 'firstname' },
			{ title: 'Rank', data: 'rank' },
			{ title: 'Department', data: 'department' },
			{ title: 'College', data: 'college' },
			{ title: 'Ext.', data: 'ext' },
			{ title: 'Email', data: 'email' },
			{ title: 'Term', data: 'term' },
			{ title: 'Charge Memberhip', data: 'charge' },
			{ title: 'Alternate', data: 'alternate' },
			{ title: 'Notes', data: 'notes' },
		],
		
	});		
	
});

function addMember(id) {
	var url = 	"{{ route('members.add', ['cid'=>':id']) }}";
	url = url.replace(':id', id);
	window.location = url;
}
	
</script>
@endsection