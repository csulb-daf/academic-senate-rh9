@extends('layouts.app')

@section('title', 'List Management')

@section('content')
<nav class="nav nav-tabs" id="tabMenu" style="text-align: center;">
	<a href="#community" data-toggle="tab" class="nav-item nav-link active">Community Members<span style="display: block">(Requires Committee Selection)</span></a>
	<a href="#charge" data-toggle="tab" class="nav-item nav-link">Charge Membership<span style="display: block">(Global List)</span></a>
	<a href="#rank" data-toggle="tab" class="nav-item nav-link">Rank<span style="display: block">(Global List)</span></a>
</nav>

<div class="tab-content">
	<div class="tab-pane active" id="community">
<!-- 		<div class="row"> -->
<!-- 			<div class="col-sm-4"> -->
				<div id="selectContainer">
					@include('partials.committee-select')
				</div>
<!-- 			</div> -->
			
<!-- 			<div class="col"> -->
				@if(session()->has('community'))
			    <div class="alert alert-success">
		        {{ session()->get('community') }}
			    </div>
				@endif
				
				<button type="button" class="btn btn-primary" id="addCommunity" style="margin-bottom: 20px;"   onclick="javascript:addCommunity();">Add Community Member</button>
				<h2 class="tableTitle" id="communityTitle">List Managment : <span></span></h2>
				<table id="communityTable" class="display" style="width: 100%"></table>
<!-- 			</div> -->
<!-- 		</div> -->
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
		
		<h2 class="tableTitle">List Management : Charge Membership<span></span></h2>
		<table id="chargeTable" class="display" style="width: 100%"></table>
		<form method="POST" id="chargeForm" action="{{ route('list.charge.add', [], false) }}">
			@csrf
			<input type="hidden" name="tabName" value="charge">	
		
			<div class="input-group">
				<button class="btn btn-primary " type="submit" style="text-transform: uppercase;">Add Charge</button>
				<input class="form-control {{ $errors->has('chargeName')? 'is-invalid' : '' }}" type="text" name="chargeName" id="chargeName" value="" >
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
		$('h2#communityTitle').find('span').text($(this).find('option:selected').text());
		communityTable.ajax.reload();
	});
	
	var communityTable = $('#communityTable').DataTable({
		autoWidth: false,
		createdRow: function(row, data, dataIndex) {
			setEdit(row, communityTable, "{{ route('community.update', [], false) }}", "{{ route('community.destroy', [], false) }}");
		},
    ajax: {
			url: 'community-members-admin',
			data: function(d) {
				d.id = $('#community .commSelect').val();
			},
			dataSrc: '',
			error: function (xhr, error, thrown) {
				communityTable.clear().draw();
			},
			complete: function() {}	
    },
		columns: [
			{ title: '#', data: null, defaultContent: '', width: '50px'},
			{ title: 'Community Members', data: 'name',
				render: function ( data, type, row ) {
					return getEditableRow(row, data);
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
			targets: [0, 2]
		}],
		order: [[ 1, 'asc' ]],
	});	
	createIndexColumn(communityTable);

	var chargeTable = $('#chargeTable').DataTable({
		autoWidth: false,
		createdRow: function(row, data, dataIndex) {
			//var uri = 	"{{ route('charge.update', [], false) }}";
			setEdit(row, chargeTable, "{{ route('charge.update', [], false) }}", "{{ route('charge.destroy', [], false) }}");
		},
    ajax: {
			url: "{{ route('list.charges.ajax', [], false) }}",
			dataSrc: '',
			error: function (xhr, error, thrown) {
				chargeTable.clear().draw();
			},
			complete: function() {}
    },
		columns: [
			{ title: '#', data: null, defaultContent: '', width: '50px'},
			{ title: 'Charge Membership', data: 'charge',
				render: function ( data, type, row ) {
					return getEditableRow(row, data);
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
		autoWidth: false,
		createdRow: function(row, data, dataIndex) {
			setEdit(row, rankTable, "{{ route('rank.update', [], false) }}", "{{ route('rank.destroy', [], false) }}");
		},
    ajax: {
			url: 'rank-admin',
			dataSrc: '',
			error: function (xhr, error, thrown) {
				rankTable.clear().draw();
			},
			complete: function() {}	
    },
		columns: [
			{ title: '#', data: null, defaultContent: '', width: '50px'},
			{ title: 'Rank', data: 'rank',
				render: function ( data, type, row ) {
					return getEditableRow(row, data);
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
	window.location = "{{ url('/list/community/add') }}";
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
				data: value,
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