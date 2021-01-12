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
		createdRow: function(row, data, dataIndex) {
			$('button.removeButton', row).click(function() {
				var that = $(this);
				var assigned = that.closest('tr').find('td.assignedTo');
				$('button.cancelRemove').not(this).trigger('click');

				if(assigned.text().trim() !== '') {
					var msg = assigned.text().trim() +' will be unassigned. Are you sure?';
					assigned.popover({
						html: true,
						content: function() {
							return '<div class="alert alert-danger">'+ msg +'</div>';
						},
						container: assigned,
						placement: 'left',
					});
					assigned.trigger('manual');
				}
				assigned.popover('show');
				$(this).hide();
				$(this).siblings('div.confirmButtons').show();
			});
			$('button.cancelRemove', row).click(function() {
				$(this).closest('div.confirmButtons').hide();
				$(this).closest('div.confirmButtons').siblings('button.removeButton').show();
				$('td.assignedTo').popover('dispose');
			});
			$('button.confirmRemove', row).click(function() {
				var that = $(this);
				var id = that.data('id');
				var chargeID = that.data('charge');
				var chargeName = that.closest('tr').find('td.chargeName').text().trim();
				var assigned = (that.closest('tr').find('td.assignedTo').text().trim()) !== '';
				
				$.ajax({
		 			headers: {
		 				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		 			},
		 			type: 'post',
		 			url: '{{ route('charge.assignments.destroy') }}',
		 			data: {
		 				id: id,
		 				charge: chargeID,
		 				comm: {{ $commID }},
		 				assigned: assigned,
		 			},
					success:  function(response) {
						//console.log(response);
						var button = $('#charges_wrapper').find('button.addedButton[data-id=' + chargeID + ']');
						button.hide();
						button.siblings('button.addButton').show();
					}
		 		});		//ajax
			});		//$('button.addButton').click
	
			
		},
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
			{ title: 'Assigned To', data: 'assigned_to', className: 'assignedTo', width: '150px'},
			{ title: 'Actions', data: null, defaultContent: '', width: '120px',
				render: function ( data, type, row ) {
					var html='\
						<button type="button" class="btn btn-danger btn-sm removeButton" data-id="'+ data.id +'">Remove</button>\
						<div class="confirmButtons" style="display: none;">\
							<button type="button" class="btn btn-danger btn-sm confirmRemove" data-id="'+ data.id +'" data-charge="'+ data.charge +'">Confirm</button>\
							<button type="button" class="btn btn-light btn-sm cancelRemove">Cancel</button>\
						</div>\
						';
						return html;
				}	
			}
		],
		columnDefs: [{		//Assignments column
			targets:  [1, 2],
			sortable: false,
		}],
	});	

	var chargesTable = $('#charges').DataTable({
		autoWidth: false,
		createdRow: function(row, data, dataIndex) {
			$('button.addButton', row).click(function() {
				$(this).hide();
				$(this).siblings('div.confirmButtons').show();
			});
			$('button.cancelButton', row).click(function() {
				$(this).closest('div.confirmButtons').hide();
				$(this).closest('div.confirmButtons').siblings('button.addButton').show();
			});
			$('button.confirmButton', row).click(function() {
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
							'assigned_to': '',
							'chargeName': chargeName,
						}).draw(false).node();

						$(row).addClass('added');
						that.closest('div.confirmButtons').hide().siblings('button.addedButton').show();
					}
		 		});		//ajax
			});		//$('button.addButton').click
		},
    ajax: {
			url: "/charge/assignments/{{ $commID }}/charges/ajax",
			data: function(d) {
				d.commID = {{ $commID }};
			},
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
					var html;
					
					if(data.assigned === 'no') {
						html='\
							<button type="button" class="btn btn-light btn-sm addButton" data-id="'+ data.id +'">Add</button>\
							<button type="button" class="btn btn-success btn-sm addedButton" style="display: none; opacity: 1;" data-id="'+ data.id +'" disabled>Added</button>\
							<div class="confirmButtons" style="display: none">\
								<button type="button" class="btn btn-success btn-sm confirmButton" data-id="'+ data.id +'">Confirm</button>\
								<button type="button" class="btn btn-light btn-sm cancelButton">Cancel</button>\
							</div>\
						';
					}
					else {
						html='\
							<button type="button" class="btn btn-light btn-sm addButton" data-id="'+ data.id +'" style="display: none;">Add</button>\
							<button type="button" class="btn btn-success btn-sm addedButton" style="opacity: 1;" data-id="'+ data.id +'" disabled>Added</button>\
							<div class="confirmButtons" style="display: none">\
								<button type="button" class="btn btn-success btn-sm confirmButton" data-id="'+ data.id +'">Confirm</button>\
								<button type="button" class="btn btn-light btn-sm cancelButton">Cancel</button>\
							</div>\
						';
					}
					
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