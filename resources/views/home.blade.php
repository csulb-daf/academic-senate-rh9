@extends('layouts.app')

@section('title', 'Home')

@section('content')

<div id="selectContainer">
	@include('partials.committee-select')
</div>

<h2 class="tableTitle">Committee: <span id="tableTitle"></span></h2>
<table id="commSearch" class="display"></table>
<form  method="POST" id="memberSearch" action="{{ route('member.search', [], false) }}" style="display: none;">
	@csrf
	<input type="hidden" name="firstname" value="">
	<input type="hidden" name="lastname" value="">
	
	<div class="form-group row">
		<label for="userSelect" class="col-form-label">Name Search</label>
		<div class="col">
			@include('partials.directory-search')
		</div>
	</div>
</form>
@endsection 

@section('scripts')
<script>
$(document).ready(function() {
	$('div.container').addClass('wide');
		
	$('.userSearch').select2({
		width: '100%',
		matcher: matchCustom,
	});
	
	$('select#userSelect').change(function() {
		var firstName = $('select#userSelect option:selected').data('firstname');
		var lastName = $('select#userSelect option:selected').data('lastname');
		$('form#memberSearch input[name=firstname]').val(firstName);
		$('form#memberSearch input[name=lastname]').val(lastName);
	});	
	
	$('#commSelect').on('change', function() {
		$('#tableTitle').text($(this).find('option:selected').text());
		table.ajax.reload();
	});

	var table = $('#commSearch').DataTable({
		responsive: true,
		autoWidth: false,
		dom: 'Blrtip',
		buttons: {
			buttons: [{
				extend: 'pdf',
				text: 'Export to PDF', 
				className: 'btn-primary',
				title: function() {
					return $('#commSelect').find('option:selected').text();
				},
				orientation: 'landscape',
				exportOptions: {
					columns: 'th:not(.campusID)'
				}	,
			}],
			dom: {
				button: {
					className: 'btn'
				}
			}
		},
		ajax: {
			url: 'comm-search',
			data: function(d) {
				d.cid = $('#commSelect').val();
			},
			dataSrc: '',
			error: function(xhr, error, thrown) {
				table.clear().draw();
			},
			complete: function() {
				$('form#memberSearch').insertAfter('#commSearch_wrapper .dataTables_length').show();
			}	
    },
		columns: [
			{ 
				title: 'Campus ID', className: 'campusID', defaultContent: '',
				render: function(data, type, row, meta) {
					if(row.campus_id == null) {
						var cid = $('#commSelect').val();
						var url = 	"{{ route('members.add', ['id'=>':id']) }}";
						url = url.replace(':id', cid);
						return '<a href="'+ url +'">VACANT</a>';
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
			{ title: 'Charge Memberhip', data: 'charge' },
			{ title: 'Alternate', data: null, defaultContent: '',
				render: function(data, type, row) {
					return data.alternate == 1? 'Y':'';
				}			
			},
			{ title: 'Notes', data: 'notes' },
		],
	});
});

</script>
@endsection