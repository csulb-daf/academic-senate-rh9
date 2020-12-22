@extends('layouts.app')

@section('title', 'Charge Management')

@section('content')
<h2 style="font-weight: bold;">{{ $commName }}</h2>
<div id="messageContainer" style="display: none;"></div>
<table id="chargeMembership" class="display"></table>
<table id="charges" class="display"></table>
@endsection 

@section('scripts')
<script>
$(document).ready(function() {
	var table = $('#chargeMembership').DataTable({
		paging: false,
		searching: false,
		//info: false,
		autoWidth: false,
    ajax: {
			url: "/charge/assignments/{{ $commID }}/ajax",
			dataSrc: '',
			error: function (xhr, error, thrown) {
				table.clear().draw();
			},
			complete: function() {
			}
    },
		columns: [
			{ title: 'Charge Name', data: 'chargeName' },
			{ title: 'Actions', data: null, defaultContent: '', width: '120px',
				render: function ( data, type, row ) {
					var html='\
						<button type="button" class="btn btn-danger btn-sm removeButton" data-id="'+ data.charge +'">Remove</button>\
						<div class="delButtons" style="display: none;">\
							<button type="button" class="btn btn-danger btn-sm confirmDelete">Confirm</button>\
							<button type="button" class="btn btn-light btn-sm cancelDelete">Cancel</button>\
						</div>\
						';
						return html;
				}	
			}
		],
		columnDefs: [{		//Assignments column
			targets:  1,
			sortable: false,
		}],
	});	

	var chargesTable = $('#charges').DataTable({
		autoWidth: false,
		createdRow: function(row, data, dataIndex) {
			$('button.addButton', row).click(function() {
				var that = $(this);
				var chargeID = that.attr('data-id');
				var chargeName = that.closest('tr').find('td.chargeName').text().trim();

				$.ajax({
		 			headers: {
		 				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		 			},
		 			type: 'post',
		 			url: '{{ route('charge.assignments.add') }}',
		 			data: {
		 				charge: chargeID,
		 				committee: {{ $commID }}
		 			},
					success:  function(response) {
						//$('#messageContainer').html('<div class="alert alert-success">'+ response.message +'</div>').fadeIn();
						var row = table.row.add({
							'charge': chargeID,
							'chargeName': chargeName,
						}).draw(false).node();

						$(row).addClass('added');
						that.hide().next('button.addedButton').show();
					}
		 		});		//ajax
			});		//$('button.addButton').click
		},
    ajax: {
			url: "/charge/assignments/{{ $commID }}/charges/ajax",
			dataSrc: '',
			error: function (xhr, error, thrown) {
				table.clear().draw();
			},
			complete: function() {}
    },
		columns: [
			{ title: 'Charge Name', data: 'charge', className: 'chargeName'},
			{ title: 'Actions', data: null, defaultContent: '', width: '120px',
				render: function ( data, type, row ) {
					var html='\
						<button type="button" class="btn btn-light btn-sm addButton" data-id="'+ data.id +'">Add</button>\
						<button type="button" class="btn btn-success btn-sm addedButton" style="display: none; opacity: 1;" disabled>Added</button>\
					';
						
					return html;
				}	
			}
		],
		columnDefs: [{		//Assignments column
			targets:  1,
			sortable: false,
		}],
	});	
	
});		//$(document).ready(function() {

</script>
@endsection