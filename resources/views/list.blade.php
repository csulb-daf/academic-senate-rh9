@extends('layouts.app')

@section('title', 'List Management')

@section('content')
<nav class="nav nav-tabs">
	<a href="#tab-table1" data-toggle="tab" class="nav-item nav-link active">Charge Membership</a>
	<a href="#tab-table2" data-toggle="tab" class="nav-item nav-link">Community Members</a>
	<a href="#tab-table3" data-toggle="tab" class="nav-item nav-link">Rank</a>
</nav>

<div class="tab-content">
	<div class="tab-pane active" id="tab-table1">
		<table id="listAdmin1" class="display" style="width: 100%"></table>
		<form id="addForm">
			<button>ADD</button>
			<input type="text">
		</form>
	</div>

	<div class="tab-pane" id="tab-table2">
		<table id="listAdmin2" class="display" style="width: 100%"></table>
	</div>
	
	<div class="tab-pane" id="tab-table3">
		<table id="listAdmin3" class="display" style="width: 100%"></table>
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
			complete: function() {}	
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
</script>
@endsection