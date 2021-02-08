@extends('layouts.app')

@section('title', 'Member Search')

@section('content')

<h2 style="font-weight: bold;">Search Result: <span id="tableTitle">{{ $firstName }} {{ $lastName }}</span></h2>
<table id="memberSearch" class="display"></table>
@endsection 

@section('scripts')
<script>
$(document).ready(function() {

	$('#commSelect').on('change', function() {
		$('#tableTitle').text($(this).find('option:selected').text());
		table.ajax.reload();
	});

	var table = $('#memberSearch').DataTable({
		autoWidth: false,
		searching: false,
		paging: false,		
		ajax: {
			url: "{{ route('member.search.result') }}",
			data: function(d) {
				d.campus_id = '{{ $campusID }}';
				d.first_name = '{{ $firstName }}';
				d.last_name = '{{ $lastName }}';
			},
			dataSrc: '',
			error: function(xhr, error, thrown) {
				table.clear().draw();
			},
			complete: function() {}	
    },
		columns: [
			{ 
				title: 'Campus ID', className: 'campusID',
				render: function(data, type, row, meta) {
					if(row.campus_id == 0) {
						return '<span class="badge badge-primary communityTag" style="color: #fff; font-size: 16px; margin-top: 10px;">CM</span>';
					}
					return row.campus_id;
				}	
			},
			{ title: 'Last Name', data: 'lastname' },
			{ title: 'First Name', data: 'firstname' },
			{ title: 'Rank', data: 'rankName' },
			{ title: 'Committee', data: 'committeename' },
			{ title: 'Term', data: 'term' },
			{ title: 'Charge Memberhip', data: 'chargeName' },
			{ title: 'Alternate', data: null, defaultContent: '',
				render: function(data, type, row) {
					return data.alternate == 1? 'Y':'';
				}			
			},
			{ title: 'Notes', data: 'notes' },
			{ title: 'Actions', data: null, defaultContent: '',
				render: function(data, type, row) {
					var url = 	"{{ route('comm.assign', ['id'=>':id'], false) }}";
					url = url.replace(':id', data.committee);
					return '<a href="'+ url +'" data-id="">Change</a>';
				}			
			},
		],
	});
});
</script>
@endsection