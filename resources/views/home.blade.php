@extends('layouts.app')

@section('title', 'Home')

@section('content')

@if(session()->has('member'))
    <div class="alert alert-success">
        {{ session()->get('member') }}
    </div>
@endif			

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
	var cid = 0;
	$('#commSelect').on('change', function() {
		cid = $(this).val();
	});
	
	$('div.container').addClass('wide');

	@if(session('committeeID') && session('committeeName'))
		$('.tableTitle').text('Committee: {{ session('committeeName') }}');
		var newOption = new Option('{{ session('committeeName') }}', {{ session('committeeID') }}, true, true);	
		$('select#commSelect').append(newOption).trigger('change');
	@endif
	
	$('select#memberSelect').on('select2:select', function(e) {
		var nameArr = e.params.data.originalName.split(','),
			lastName = nameArr[0].trim(),
			firstName = nameArr[1].trim();
		
		$('form#memberSearch input[name=firstname]').val(firstName);
		$('form#memberSearch input[name=lastname]').val(lastName);
		params = $('form#memberSearch').serialize();
		var url = "{{ route('member.search') }}?"+ params;
		table.rowGroup().dataSrc('committeename');	//For grouping under committee name
		table.ajax.url(url).load(function(response) {
			$('.tableTitle').text('Search Results: '+ firstName +' '+ lastName +' ('+ response[0]['emp_type'] +')');
			$('#commSearch .empType').hide().addClass('hide');
			$('#commSearch tr#altHeading').hide();
		});
		$('#commSelect').val(null).trigger('change');		//reset select box
	});
		
	$('select#commSelect').on('select2:select', function(e) {
		$('.tableTitle').text('Committee: '+ e.params.data.text);	
		table.ajax.url("{{ route('committee.search') }}").load(function(response) {
			$('#commSearch .empType').show().removeClass('hide');
			$('#commSearch tr#altHeading').show();
		});
		$('#memberSelect').val(null).trigger('change');		//reset select box
	});

	var table = $('#commSearch').DataTable({
		responsive: true,
		autoWidth: false,
		rowGroup: {
			emptyDataGroup: null
		},
		createdRow: function(row, data, dataIndex) {
			if(data.alternate) {
				$(row).addClass('added');
			}
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

					var data = table.column(13).data().toArray();		//alternate column bg color
					$.each(data, function(key, val) {
					  if(val.alternate == 1) {
							row = doc.content[1].table.body[key+1];
							for (col=0; col < row.length; col++) {
								row[col].fillColor =  '#b2e4c7';
							}
					  }
					});	

					doc['footer'] = function() {
						return {
							text: [
								'Shaded Rows Denote Alternates\n',
								'Revision Date: ', { text: jsDate.toString() }
							],
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
			{ title: 'Employee Type', data: 'emp_type', className: 'empType' },
			{ title: 'Employee Sort', data: 'emp_sort', defaultContent: '200', visible: false, className: 'hide' },
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
			{ title: 'Actions', data: null, defaultContent: '', className: 'actions', responsivePriority: 2, width: '60px',
				render: function(data, type, row) {
					if(data.id == null) {
						var url = 	"{{ route('members.add.view', ['cid'=>':cid', 'mid'=>':mid', 'chid'=>':chid']) }}";
						url = url.replace(':cid', cid);
						url = url.replace(':mid', 0);
						url = url.replace(':chid', data.chargeID);

						return '<a href="'+ url +'" class="btn btn-light btn-sm border assign" title="Assign" data-toggle="tooltip"><img src="{{ asset('images/external-link.svg') }}"></a>';
					}
					var changeUrl = 	"{{ route('members.edit', ['cid'=>':cid', 'mid'=>':mid']) }}";
					changeUrl = changeUrl.replace(':cid', data.committee);
					changeUrl = changeUrl.replace(':mid', data.id);

					var assignUrl = 	"{{ route('comm.assign', ['cid'=>':cid']) }}";
					assignUrl = assignUrl.replace(':cid', data.committee);

					var html = '\
						<a href="'+ changeUrl +'" class="btn btn-light btn-sm border change" title="Change" data-toggle="tooltip"><img src="{{ asset('images/pencil-square.svg') }}"></a>\
						<a href="'+ assignUrl +'" class="btn btn-light btn-sm border assign" title="Assign" data-toggle="tooltip"><img src="{{ asset('images/external-link.svg') }}"></a>\
					';

					return html;
				}			
			},
		],
		columnDefs: [{
			targets:  [3, 14, 15],
			sortable: false,
		}],
		order: [[3, 'asc'], [1, 'asc']],
	});		//DataTable

	table.on('draw', function () {
		$('[data-toggle="tooltip"]').tooltip();
		$(this).find('tr.added:first').before('<tr id="altHeading" class="dtrg-group dtrg-start dtrg-level-0"><td colspan="14">Alternates</td></tr>');
	});
	
});	//document.ready
</script>
@endsection