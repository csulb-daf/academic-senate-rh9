@extends('layouts.app')

@section('title', 'List Management')

@section('content')
<nav class="nav nav-tabs">
	<a href="#charge" data-toggle="tab" class="nav-item nav-link active">Charge Membership</a>
	<a href="#community" data-toggle="tab" class="nav-item nav-link">Community Members</a>
	<a href="#rank" data-toggle="tab" class="nav-item nav-link">Rank</a>
</nav>

<div class="tab-content">
	<div class="tab-pane active" id="charge">

	<div class="row">
		<div class="col-sm-4">
			@include('partials.comm-search')		
		</div>
	
		<div class="col">
			@if ($errors->any())
				<div class="alert alert-danger">
					<ul>
						@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
			@endif

			<table id="listAdmin1" class="display" style="width: 100%"></table>

			<form method="POST" id="chargeForm" action="{{ route('charge.add') }}">
				@csrf

				<div class="input-group">
					<button class="btn btn-primary " type="submit">ADD CHARGE</button>
					<input class="form-control" type="text" name="charge_membership" id="charge_membership" value="{{ old('charge_membership') }}" >
				</div>
			</form>	
		</div>
</div>
</div>

	<div class="tab-pane" id="community">
		<div class="row">
			<div class="col-sm-4">
				@include('partials.comm-search')		
			</div>
		
			<div class="col">
				<table id="listAdmin2" class="display" style="width: 100%"></table>
				<button type="button" class="btn btn-primary" id="addCommunity" style="display: none; float: left;"  onclick="javascript:addCommunity();">Add Community Member</button>
			</div>
		</div>
	</div>
	
	<div class="tab-pane" id="rank">
		<div class="col">
			@if ($errors->any())
				<div class="alert alert-danger">
					<ul>
						@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
			@endif
	
		<table id="listAdmin3" class="display" style="width: 100%"></table>
		
			<form method="POST" id="rankForm" action="{{ route('rank.add') }}">
				@csrf

				<div class="input-group">
					<button class="btn btn-primary " type="submit">ADD RANK</button>
					<input class="form-control" type="text" name="rank" id="rank" value="{{ old('rank') }}" >
				</div>
			</form>	
		
	</div>
	
</div>
</div>
@endsection 

@section('scripts')
<script>
$(document).ready(function() {
	var table1 = $('#listAdmin1').DataTable({
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
			{ title: 'Charge Membership', data: 'charge_membership' },
			{ title: 'Actions', data: null, defaultContent: '',
				render: function ( data, type, row ) {
    			return '<button>Edit</button>';
				}			
			}
		],
		columnDefs: [{		//index column
			targets: 0,
			sortable: false,
			"class": "index"
		}],
		order: [[ 1, 'asc' ]],		//sort by index column
		
		columnDefs: [{		//Actions column
			targets:  2,
			sortable: false,
		}],
		
	});
	createIndexColumn(table1);
	
	var table2 = $('#listAdmin2').DataTable({
    ajax: {
			url: 'community-members-admin',
			dataSrc: '',
			error: function (xhr, error, thrown) {
				table1.clear().draw();
			},
			complete: function() {
				$("button#addCommunity").prependTo("#listAdmin2_wrapper").show();
			}	
    },
		columns: [
			{ title: '#', data: null, defaultContent: '' },
			{ title: 'Community Members', data: 'name'},
			{ title: 'Actions', data: null, defaultContent: '',
				render: function ( data, type, row ) {
    			return '<button>Edit</button>';
				}			
			}
		],
		columnDefs: [{		//index column
			sortable: false,
			"class": "index",
			targets: 0
		}],
		order: [[ 1, 'asc' ]],
		
		columnDefs: [{		//Actions column
			targets:  2,
			sortable: false,
		}],
		
	});	
	createIndexColumn(table2);

	var table3 = $('#listAdmin3').DataTable({
    ajax: {
			url: 'rank-admin',
			dataSrc: '',
			error: function (xhr, error, thrown) {
				table1.clear().draw();
			},
			complete: function() {}	
    },
		columns: [
			{ title: '#', data: null, defaultContent: '' },
			{ title: 'Rank', data: 'rank'},
			{ title: 'Actions', data: null, defaultContent: '',
				render: function ( data, type, row ) {
    			return '<button>Edit</button>';
				}			
			}
		],
		columnDefs: [{		//index column
			sortable: false,
			"class": "index",
			targets: 0
		}],
		order: [[ 1, 'asc' ]],
		
		columnDefs: [{		//Actions column
			targets:  2,
			sortable: false,
		}],
		
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