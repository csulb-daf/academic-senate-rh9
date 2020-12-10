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
				@include('partials.comm-search')		
<!-- 			</div> -->
			
<!-- 			<div class="col"> -->
				@if(session()->has('community'))
				    <div class="alert alert-success">
				        {{ session()->get('community') }}
				    </div>
				@endif			
				
				<table id="communityTable" class="display" style="width: 100%"></table>
				<button type="button" class="btn btn-primary" id="addCommunity" style="display: none; float: left;"  onclick="javascript:addCommunity();">Add Community Member</button>
<!-- 			</div> -->
					
<!-- 		</div> -->
	</div>

	<div class="tab-pane" id="charge">
<!-- 	<div class="row"> -->
<!-- 		<div class="col"> -->
			@if ($errors->has('charge_membership'))
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
			
			<table id="chargeTable" class="display" style="width: 100%"></table>

			<form method="POST" id="chargeForm" action="{{ route('charge.add') }}">
				@csrf
				<input type="hidden" name="tabName" value="charge">

				<div class="input-group">
					<button class="btn btn-primary " type="submit">ADD CHARGE</button>
					<input class="form-control {{ $errors->has('charge_membership')? 'is-invalid' : '' }}" type="text" name="charge_membership" id="charge_membership" value="" >
				</div>
			</form>	
<!-- 		</div> -->
<!-- </div> -->
</div>

	
	<div class="tab-pane" id="rank">
<!-- 		<div class="col"> -->
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
		
<!-- 	</div> -->
	
</div>
</div>
@endsection 

@section('scripts')
<script>
$(document).ready(function() {
	$('#tabMenu a[href="#{{ old('tabName') }}"]').tab('show')

	var table1 = $('#chargeTable').DataTable({
	
    ajax: {
			url: 'charge-admin',
			dataSrc: '',
			error: function (xhr, error, thrown) {
				table1.clear().draw();
			},
			complete: function() {}	
    },
		columns: [
			{ title: '#', data: null, defaultContent: '' },
			{ title: 'Charge Membership', data: 'charge_membership',  width: '70%'},
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
</script>
@endsection