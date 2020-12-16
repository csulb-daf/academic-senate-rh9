@extends('layouts.app')

@section('title', 'List Management')

@section('content')
<nav class="nav nav-tabs" id="tabMenu" style="text-align: center;">
	<a href="#charge" data-toggle="tab" class="nav-item nav-link active">Charge Membership<span style="display: block">(Requires Committee Selection)</span></a>
	<a href="#community" data-toggle="tab" class="nav-item nav-link">Community Members<span style="display: block">(Requires Committee Selection)</span></a>
	<a href="#rank" data-toggle="tab" class="nav-item nav-link">Rank<span style="display: block">(Global List)</span></a>
</nav>

<div class="tab-content">
	<div class="tab-pane" id="community">
		<select class="commSelect form-control" style="margin: 20px 0;" name="commSelect" >
			<option value="" disabled selected>Select Committee</option>
		<!-- 	<option value="0">Unassigned</option> -->
			@foreach($communityComms as $comm)
				<option value="{{ $comm->id }}">{{ $comm->committeename }}</option>
			@endforeach
		</select>
		
		@if(session()->has('community'))
		    <div class="alert alert-success">
		        {{ session()->get('community') }}
		    </div>
		@endif			
		
		<h2 class="tableTitle">List Managment : <span></span></h2>
		<table id="communityTable" class="display" style="width: 100%"></table>
		<button type="button" class="btn btn-primary" id="addCommunity" style="display: none; float: left;"  onclick="javascript:addCommunity();">Add Community Member</button>
	</div>

	<div class="tab-pane active" id="charge">
		<select class="commSelect form-control" style="margin: 20px 0;" name="commSelect" >
			<option value="" disabled selected>Select Committee</option>
		<!-- 	<option value="0">Unassigned</option> -->
			@foreach($chargeComms as $comm)
				<option value="{{ $comm->id }}">{{ $comm->committeename }}</option>
			@endforeach
		</select>
	
		@if(session()->has('charge'))
		    <div class="alert alert-success">
		        {{ session()->get('charge') }}
		    </div>
		@endif			
		
		<h2 class="tableTitle">List Management : <span></span></h2>
		<table id="chargeTable" class="display" style="width: 100%"></table>
		<button type="button" class="btn btn-primary" id="addCharge" style="display: none; float: left;"  onclick="javascript:addCharge();">Add Charge Membership</button>
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
				<input class="form-control {{ $errors->has('rank')? 'is-invalid' : '' }}" type="text" name="rank" id="rank" value="" >
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

	/*** Table title ***/
	$('.commSelect').on('change', function() {
		//console.log($(this).find('option:selected').text());
		
		$(this).siblings('h2.tableTitle').find('span').text($(this).find('option:selected').text());
		table1.ajax.reload();
		table2.ajax.reload();
	});
	
	var table1 = $('#chargeTable').DataTable({
		createdRow: function(row, data, dataIndex) {
			setEdit(row, table1, '/list/charge/update', '/list/charge/destroy');
		},
    ajax: {
			url: 'charge-admin',
			data: function(d) {
				d.id = $('#charge .commSelect').val();
			},
			dataSrc: '',
			error: function (xhr, error, thrown) {
				table1.clear().draw();
			},
			complete: function() {
				$("button#addCharge").prependTo("#chargeTable_wrapper").show();
			}	
    },
		columns: [
			{ title: '#', data: null, defaultContent: '' },
			{ title: 'Charge Membership', data: 'charge_membership',  width: '70%',
				render: function ( data, type, row ) {
					return getEditableRow(row, data);
				}
			},
			{ title: 'Assigned to Committee', data: 'committee',  width: '10%'},
			{ title: 'Actions', data: null, defaultContent: '',
				render: function ( data, type, row ) {
    			return getEditButtons(row.id);
				}			
			}
		],
		columnDefs: [{
			sortable: false,
			"class": "index",
			targets: [0, 1, 2, 3],
		}],
		order: [[ 1, 'asc' ]],
		fixedColumns: true,
	});
	createIndexColumn(table1);
	
	var table2 = $('#communityTable').DataTable({
		createdRow: function(row, data, dataIndex) {
			setEdit(row, table2, '/list/community/update', '/list/community/destroy');
		},
    ajax: {
			url: 'community-members-admin',
			data: function(d) {
				d.id = $('#community .commSelect').val();
			},
			dataSrc: '',
			error: function (xhr, error, thrown) {
				table2.clear().draw();
			},
			complete: function() {
				$("button#addCommunity").prependTo("#communityTable_wrapper").show();
			}	
    },
		columns: [
			{ title: '#', data: null, defaultContent: '' },
			{ title: 'Community Members', data: 'name',
				render: function ( data, type, row ) {
					return getEditableRow(row, data);
				}
			},
			{ title: 'Actions', data: null, defaultContent: '',
				render: function ( data, type, row ) {
					return getEditButtons(row.id);
				}			
			}
		],
		columnDefs: [{
			sortable: false,
			"class": "index",
			targets: [0, 2]
		}],
		order: [[ 1, 'asc' ]],
		fixedColumns: true,
	});	
	createIndexColumn(table2);

	var table3 = $('#rankTable').DataTable({
		createdRow: function(row, data, dataIndex) {
			setEdit(row, table3, '/list/rank/update', '/list/rank/destroy');
		},
    ajax: {
			url: 'rank-admin',
			dataSrc: '',
			error: function (xhr, error, thrown) {
				table3.clear().draw();
			},
			complete: function() {}	
    },
		columns: [
			{ title: '#', data: null, defaultContent: '' },
			{ title: 'Rank', data: 'rank',
				render: function ( data, type, row ) {
					return getEditableRow(row, data);
				}
			},
			{ title: 'Actions', data: null, defaultContent: '',
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
		fixedColumns: true,
	});	
	createIndexColumn(table3);
});

function createIndexColumn(table) {
	table.on( 'order.dt search.dt', function() {		//index column
		table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
			cell.innerHTML = i+1;
		});
	}).draw();
}

function addCommunity() {
	window.location = "{{ url('/list/community/add') }}";
}
function addCharge() {
	window.location = "{{ url('/list/charge/add') }}";
}

function getEditableRow(row, data) {
	return '<span class="edit" data-id="'+ row.id +'">'+ data +'</span><img src="/images/check.svg" class="saved" style="width: 35px; display: none;">';	
}
function getEditButtons(id) {
	var html='\
		<div class="editButtons">\
				<button type="button" class="btn btn-light btn-sm editButton">Edit</button>\
				<button type="button" class="btn btn-danger btn-sm deleteButton">Delete</button>\
				<img src="/images/check.svg" class="saved" style="width: 35px; display: none;">\
			</div>\
			<div class="delButtons" style="display: none;">\
					<button type="button" class="btn btn-danger btn-sm confirmDelete" data-id="'+ id +'">Confirm</button>\
					<button type="button" class="btn btn-light btn-sm cancelDelete">Cancel</button>\
				</div>\
		';
		return html;
}
function setEdit(row, table, updateURL, delURL) {
	$('.edit', row).editable(function(value, settings) {
		var id = $(this).attr('data-id');

		$.ajax({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			type: 'post',
			url: updateURL,
			data: {
				id: id,
				charge: value,
			},
		});
		return value;
	}, {
		indicator : '<img src="/images/spinner.svg" />',
		cancel: 'Cancel',
		cancelcssclass: 'btn btn-light btn-sm cancelButton',
		submit : 'Save',
		submitcssclass:  'btn btn-light btn-sm saveButton',
		//onblur: 'ignore',
		onedit: function() {
			$(this).closest('tr').find('.editButtons button').prop('disabled', true);
		},
		onreset: function() {
			$(this).closest('tr').find('.editButtons button').prop('disabled', false);
			$(this).closest('tr').find('.editButtons button').prop('disabled', false);
		},
		onsubmit: function() {
			$(this).closest('td').find('img.saved').show();
		},
	});

	$('button.editButton', row).click(function() {
		$(this).closest('tr').find('span.edit').trigger('click');
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
		var id = $(this).attr('data-id');
		
		$.ajax({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			type: 'post',
			url: delURL,
			data: {
				id: id,
			},
		});
		
		table
			.row( $(this).parents('tr') )
			.remove()
			.draw();
	});
}
</script>
@endsection