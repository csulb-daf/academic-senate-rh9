@extends('layouts.app')

@section('title', 'Committee Management')

@section('content')
<table id="memberAdmin" class="display"></table>
<button type="button" class="btn btn-primary" id="addMember" style="display: none; float: left;"  onclick="javascript:addMember({{ $cid }});">Add New Committee Member</button>
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
				$('.dataTables_length').css({
					'float' : 'right',
					'margin-left' : '30px'
				});
				$("button#addMember").prependTo("#memberAdmin_wrapper").show();
			}
    },
		columns: [
			{ title: 'Committee Name', data: 'committeename' },
			{ title: 'Assignments', data: null, defaultContent: '',
				render: function ( data, type, row ) {
    			return '<button>Edit</button>';
				}			
			}
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