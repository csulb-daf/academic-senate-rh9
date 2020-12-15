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
		@include('partials.comm-search')		
	
		@if(session()->has('community'))
		    <div class="alert alert-success">
		        {{ session()->get('community') }}
		    </div>
		@endif			
		
		<table id="communityTable" class="display" style="width: 100%"></table>
		<button type="button" class="btn btn-primary" id="addCommunity" style="display: none; float: left;"  onclick="javascript:addCommunity();">Add Community Member</button>
	</div>

	<div class="tab-pane active" id="charge">
		@include('partials.comm-search')
	
		@if(session()->has('charge'))
		    <div class="alert alert-success">
		        {{ session()->get('charge') }}
		    </div>
		@endif			
		
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
	
	$('select#commSelect').on('change', function() {
		  //console.log($(this).val());
		  $('input#commAssign').val($(this).val());
	});

	var table1 = $('#chargeTable').DataTable({
	
    ajax: {
			url: 'charge-admin',
			dataSrc: '',
			error: function (xhr, error, thrown) {
				table1.clear().draw();
			},
			complete: function() {
				
				$("button#addCharge").prependTo("#chargeTable_wrapper").show();

				$('.edit').editable(function(value, settings) {
					var id = $(this).attr('data-id');

					$.ajax({
						headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						},
						type: 'post',
						url: '/list/charge/update',
						data: {
							id: id,
							charge: value,
						},
					});
					return value;
				}, {
					indicator : '<img src="/images/spinner.svg" />',
				});

				$('button.editButton').click(function() {
					$(this).hide();
					$(this).next('button.saveButton').show();
					$(this).closest('tr').find('span.edit').trigger('click');
				});
				$('button.saveButton').click(function() {
					$(this).closest('tr').find('span.edit form').trigger('submit');
				});		
			}	
    },
		columns: [
			{ title: '#', data: null, defaultContent: '' },
			{ title: 'Charge Membership', data: 'charge_membership',  width: '70%',
				render: function ( data, type, row ) {
					//console.log('row', row);
					return '<span class="edit" data-id="'+ row.id +'">'+ data +'</span>';
				}
			},
			{ title: 'Assigned to Committee', data: 'committee',  width: '10%'},
			{ title: 'Actions', data: null, defaultContent: '',
				render: function ( data, type, row ) {
    			var html='\
    				<button type="button" class="btn btn-default btn-sm editButton">Edit</button>\
    				<button type="button" class="btn btn-default btn-sm saveButton" style="display: none;">Save</button>\
    				<button type="button" class="btn btn-default btn-sm">Delete</button>\
    			';
    			return html;
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
	createIndexColumn(table1);
	
	var table2 = $('#communityTable').DataTable({
    ajax: {
			url: 'community-members-admin',
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
			{ title: 'Community Members', data: 'name'},
			{ title: 'Actions', data: null, defaultContent: '',
				render: function ( data, type, row ) {
    			var html='\
    				<button type="button" class="btn btn-default btn-sm"><img src="/images/pencil-square.svg" style="width: 22px;"></button>\
    				<button type="button" class="btn btn-default btn-sm"><img src="/images/x-circle-fill.svg" style="width: 22px;"></span></button>\
    			';
    			
    			return html;
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
			{ title: 'Rank', data: 'rank'},
			{ title: 'Actions', data: null, defaultContent: '',
				render: function ( data, type, row ) {
    			var html='\
    				<button type="button" class="btn btn-default btn-sm" onclick="javascript:edit(data.id);"><img src="/images/pencil-square.svg" style="width: 22px;"></button>\
    				<button type="button" class="btn btn-default btn-sm" onclick="javascript:delete(data.id);"><img src="/images/x-circle-fill.svg" style="width: 22px;"></span></button>\
    			';
    			
    			return html;
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



</script>
@endsection