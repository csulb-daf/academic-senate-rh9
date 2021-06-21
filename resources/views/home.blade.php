@extends('layouts.app')

@section('title', 'Home')

@section('content')

<div id="selectContainer">
	@include('partials.committee-search')
</div>

<h2 class="tableTitle">Please select a committee from the 'select committee' drop down or a name from the name search.</h2>
<table id="commSearch" class="display"></table>
<form  method="POST" id="memberSearch" action="javascript:void(0);" style="display: none;">
	@csrf
	<input type="hidden" name="firstname" value="">
	<input type="hidden" name="lastname" value="">
	
	<div class="form-group row">
		<label for="memberSelect" class="col-form-label">Name Search</label>
		<div class="col">
			@include('partials.member-search')
		</div>
	</div>
</form>
@endsection 

@section('scripts')
<script>
$(document).ready(function() {
	$('div.container').addClass('wide');
	
	$('select#memberSelect').on('select2:select', function(e) {
		var nameArr = e.params.data.originalName.split(','),
			lastName = nameArr[0].trim(),
			firstName = nameArr[1].trim();
		
		$('.tableTitle').text('Search Results: '+ firstName +' '+ lastName);
		$('form#memberSearch input[name=firstname]').val(firstName);
		$('form#memberSearch input[name=lastname]').val(lastName);
		params = $('form#memberSearch').serialize();
		var url = "{{ route('member.search') }}?"+ params;
		table.rowGroup().dataSrc('committeename');	//For grouping under committee name
		table.ajax.url(url).load();
		$('#commSelect').val(null).trigger('change');		//reset select box
	});
		
	$('select#commSelect').on('select2:select', function(e) {
		$('.tableTitle').text('Committee: '+ e.params.data.text);	
		table.ajax.url("{{ route('committee.search') }}").load();
		$('#memberSelect').val(null).trigger('change');		//reset select box
	});

	var table = $('#commSearch').DataTable({
		responsive: true,
		autoWidth: false,
		rowGroup: {
			emptyDataGroup: null
		},
		dom: 'Blrtip',
		buttons: {
			buttons: [{
				extend: 'pdf',
				text: 'Export to PDF', 
				className: 'btn-primary',
				title: function() {
					return $('.tableTitle').text();
				},
				orientation: 'landscape',
				exportOptions: {
					columns: ['th:not(.campusID, .actions, .hide)', 'th.committee']
				}	,

				customize: function(doc) {
					//Create a date string that we use in the footer.
					var now = new Date();
					var jsDate = (now.getMonth()+1) +'-'+ now.getDate() +'-'+ now.getFullYear();
					
					doc['footer'] = function() {
						return {
							text: ['Revision Date: ', { text: jsDate.toString() }],
							margin: [20, 0, 20, 0],
							alignment: 'center',
						}
					}
				},
			}],
			dom: {
				button: {
					className: 'btn'
				}
			}
		},
		ajax: {
 			headers: {
 				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
 			},
			type: 'post',
			url: "{{ route('committee.search') }}",
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
			{ title: 'Committee', data: 'committeename', defaultContent: '', visible: false, 
				createdCell: function(td, cellData, rowData, row, col) {
					var header = table.column(col).header();
					if (typeof rowData.committeename !== 'undefined') {
						$(td).removeClass('hide').addClass('committee');
						$(header).removeClass('hide').addClass('committee');
					}
					else {
						$(td).removeClass('committee').addClass('hide');
						$(header).removeClass('committee').addClass('hide');
					}
				}
			},
			{
				title: 'Campus ID', className: 'campusID', defaultContent: '', responsivePriority: 1,
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
			{ title: 'Charge Memberhip', data: 'charge' },
			{ title: 'Alternate', data: null, defaultContent: '',
				render: function(data, type, row) {
					return data.alternate == 1? 'Y':'';
				}			
			},
			{ title: 'Notes', data: 'notes' },
			{ title: 'Actions', data: null, defaultContent: '', className: 'actions', responsivePriority: 2,
				render: function(data, type, row) {
					if(data.id == null) {
						var cid = $('#commSelect').val();
						var url = 	"{{ route('members.add.view', ['id'=>':id', 'mid'=>':mid', 'chid'=>':chid']) }}";
						url = url.replace(':id', cid);
						url = url.replace(':mid', 0);
						url = url.replace(':chid', data.chargeID);

						return '<a href="'+ url +'" class="btn btn-light btn-sm border">Assign</button>';
					}
					var url = 	"{{ route('members.edit', ['cid'=>':cid', 'user'=>':uid']) }}";
					url = url.replace(':cid', data.committee);
					url = url.replace(':uid', data.id);
					return '<a href="'+ url +'" data-id="" class="btn btn-light btn-sm border">Change</a>';
				}			
			},
		],
		columnDefs: [{
			targets:  [3, 14, 15],
			sortable: false,
		}],
		order: [[3, 'asc'], [1, 'asc']],
	});		//DataTable
});

</script>
@endsection