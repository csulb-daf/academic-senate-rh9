@extends('layouts.app')

@section('title', 'List Management')

@section('content')
<nav class="nav nav-tabs" id="tabMenu" style="text-align: center;">
	<a href="#community" data-toggle="tab" class="nav-item nav-link active">Community Members<span style="display: block">(Global List)</span></a>
	<a href="#charge" data-toggle="tab" class="nav-item nav-link">Charge Membership<span style="display: block">(Global List)</span></a>
	<a href="#rank" data-toggle="tab" class="nav-item nav-link">Rank<span style="display: block">(Global List)</span></a>
</nav>

<ul class="nav flex-column nav-pills" id="tabMenuMobile" style="text-align: center;">
	<li class="nav-item">
		<a class="nav-link active" href="#community" data-toggle="tab">Community Members (Global List)</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" href="#charge" data-toggle="tab">Charge Membership (Global List)</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" href="#rank" data-toggle="tab">Rank (Global List)</a>
	</li>
</ul>

<div id="validation-errors"></div>
<div class="tab-content">
	<div class="tab-pane active" id="community">
				
		<button type="button" class="btn btn-primary" id="addCommunity" style="margin-bottom: 20px;"   onclick="javascript:addCommunity();">Add Community Member</button>
		<h2 class="tableTitle" id="communityTitle">List Managment : Community Members</h2>
		<table id="communityTable" class="display" style="width: 100%"></table>
	</div>
	
	<div class="tab-pane" id="charge">
		@if ($errors->has('chargeName'))
			<div class="alert alert-danger">
				<ul>
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif
	
		@if(session()->has('charge'))
		    <div class="alert alert-success">
		        {{ session()->get('charge') }}
		    </div>
		@endif			
		
		<h2 class="tableTitle">List Management : Charge Membership</h2>
		<table id="chargeTable" class="display" style="width: 100%"></table>
		<form method="POST" id="chargeForm" action="{{ route('list.charge.add', [], false) }}">
			@csrf
			<input type="hidden" name="tabName" value="charge">	
		
			<div class="input-group">
				<button class="btn btn-primary " type="submit" style="text-transform: uppercase;">Add Charge</button>
				<input class="form-control {{ $errors->has('chargeName')? 'is-invalid' : '' }}" type="text" name="chargeName" id="chargeName" value="" required>
			</div>
		</form>	
	</div>
	
	<div class="tab-pane" id="rank">
		@if ($errors->has('rank'))
			<div class="alert alert-danger">
				<ul>
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif
		
		@if(session()->has('rank'))
		    <div class="alert alert-success">
		        {{ session()->get('rank') }}
		    </div>
		@endif			
	
		<h2 class="tableTitle">List Management : Rank</h2>	
		<table id="rankTable" class="display" style="width: 100%"></table>
	
		<form method="POST" id="rankForm" action="{{ route('rank.add') }}">
			@csrf
			<input type="hidden" name="tabName" value="rank">	
		
			<div class="input-group">
				<button class="btn btn-primary " type="submit">ADD RANK</button>
				<input class="form-control {{ $errors->has('rank')? 'is-invalid' : '' }}" type="text" name="rank" id="rank" value="" required>
			</div>
		</form>	
	</div>
</div>
@endsection 

@section('scripts')
<script>
$(document).ready(function() {
	/*** Show correct tab after form submit ***/
	$('#tabMenu a[href="#{{ old('tabName') }}"]').tab('show');

	var communityTable = $('#communityTable').DataTable({
		responsive: true,
		autoWidth: false,
		createdRow: function(row, data, dataIndex) {
			setButtonActions(row, "{{ route('community.update', [], false) }}", "{{ route('community.destroy', [], false) }}");
		},
    ajax: {
    	url: "{{ route('list.community.admin', [], false) }}",
			dataSrc: '',
			error: function (xhr, error, thrown) {
				communityTable.clear().draw();
			},
			complete: function() {}	
    },
		columns: [
			{ title: '#', data: null, defaultContent: '', width: '15px'},
			{ title: 'Last Name', data: 'lastname', className: 'editable',
				createdCell: function(td, cellData, rowData, row, col) {
					$(td).attr('data-name', 'lastname');
				}
			},
			{ title: 'First Name', data: 'firstname', className: 'editable',
				createdCell: function(td, cellData, rowData, row, col) {
					$(td).attr('data-name', 'firstname');
				}
			},
			{ title: 'Email', data: 'email', className: 'editable',
				createdCell: function(td, cellData, rowData, row, col) {
					$(td).attr('data-name', 'email');
				}
			},
			{ title: 'Notes', data: 'notes', className: 'editable',
				createdCell: function(td, cellData, rowData, row, col) {
					$(td).attr('data-name', 'notes');
				}
			},
			{ title: 'Actions', data: null, defaultContent: '', width: '120px', responsivePriority: 1,
				render: function(data, type, row) {
					return getEditButtons(row.id);
				}			
			}
		],
		columnDefs: [{
			sortable: false,
			targets: [0, 4, 5]
		}],
		order: [[ 1, 'asc' ]],
	});	
	createIndexColumn(communityTable);

	var chargeTable = $('#chargeTable').DataTable({
		responsive: true,
		autoWidth: false,
		createdRow: function(row, data, dataIndex) {
			setButtonActions(row, "{{ route('charge.update', [], false) }}", "{{ route('charge.destroy', [], false) }}");
		},
    ajax: {
			url: "{{ route('list.charge.admin', [], false) }}",
			dataSrc: '',
			error: function (xhr, error, thrown) {
				chargeTable.clear().draw();
			},
			complete: function() {}
    },
		columns: [
			{ title: '#', data: null, defaultContent: '', width: '15px'},
			{ title: 'Charge Membership', data: 'charge', className: 'editable',
				createdCell: function(td, cellData, rowData, row, col) {
					$(td).attr('data-name', 'chargeName');
				}
			},
			{ title: 'Actions', data: null, defaultContent: '', width: '120px',
				render: function ( data, type, row ) {
    			return getEditButtons(row.id);
				}			
			}
		],
		columnDefs: [{
			sortable: false,
			"class": "index",
			targets: [0, 2],
		}],
		order: [[ 1, 'asc' ]],
	});
	createIndexColumn(chargeTable);
	
	var rankTable = $('#rankTable').DataTable({
		responsive: true,
		autoWidth: false,
		createdRow: function(row, data, dataIndex) {
			setButtonActions(row, "{{ route('rank.update', [], false) }}", "{{ route('rank.destroy', [], false) }}");
		},
    ajax: {
    	url: "{{ route('list.rank.admin', [], false) }}",
			dataSrc: '',
			error: function (xhr, error, thrown) {
				rankTable.clear().draw();
			},
			complete: function() {}	
    },
		columns: [
			{ title: '#', data: null, defaultContent: '', width: '15px'},
			{ title: 'Rank', data: 'rank', className: 'editable',
				createdCell: function(td, cellData, rowData, row, col) {
					$(td).attr('data-name', 'rankName');
				}
			},
			{ title: 'Actions', data: null, defaultContent: '', width: '120px',
				render: function ( data, type, row ) {
					return getEditButtons(row.id);					
				}			
			}
		],
		columnDefs: [{
			sortable: false,
			"class": "index",
			targets: [0, 2],
		}],
		order: [[ 1, 'asc' ]],
	});	
	createIndexColumn(rankTable);
});

function createIndexColumn(table) {
	table.on( 'order.dt search.dt', function() {		//index column
		table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
			cell.innerHTML = i+1;
		});
	}).draw();
}

function addCommunity() {
	window.location = "{{ route('community.add', [], false) }}";
}
function getEditButtons(id) {
	var html='\
		<div class="editButtons">\
			<button type="button" class="btn btn-light btn-sm border editButton">Edit</button>\
			<button type="button" class="btn btn-danger btn-sm deleteButton">Delete</button>\
		</div>\
		<div class="submitButtons" style="display: none;">\
			<button type="button" class="btn btn-success btn-sm submit" data-id="'+ id +'">Submit</button>\
			<button type="button" class="btn btn-light btn-sm border cancelEdit">Cancel</button>\
		</div>\
		<div class="delButtons" style="display: none;">\
			<button type="button" class="btn btn-danger btn-sm confirmDelete" data-id="'+ id +'">Confirm</button>\
			<button type="button" class="btn btn-light btn-sm border cancelDelete">Cancel</button>\
		</div>\
		<span class="badge badge-success saved" style="font-size: 14px; padding: 5px 10px; display: none;">Saved</span>\
	';
	return html;
}
function setButtonActions(row, updateURL, delURL) {
	$('button.editButton', row).click(function() {
		editRow(row);
		$(this).closest('div.editButtons').hide();
		$(this).closest('div.editButtons').siblings('div.submitButtons').show();
	});
	$('button.cancelEdit', row).click(function() {
		cancelEdit(row);
		$(this).closest('div.submitButtons').hide();
		$(this).closest('div.submitButtons').siblings('div.editButtons').show();
	});
	$('button.submit', row).click(function() {
		var id = $(this).data('id');
		submit(id, row, updateURL);
	});
	$('button.deleteButton', row).click(function() {
		$(this).closest('div.editButtons').hide();
		$(this).closest('div.editButtons').siblings('div.delButtons').show();
	});
	$('button.cancelDelete', row).click(function() {
		$(this).closest('div.delButtons').hide();
		$(this).closest('div.delButtons').siblings('div.editButtons').show();
	});
	$('button.confirmDelete', row).click(function() {
		var id = $(this).data('id');
		destroy(id, row, delURL);
	});
}
</script>
@endsection