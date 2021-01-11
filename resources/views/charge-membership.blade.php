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
				var chargeID = that.attr('data-id');
				var chargeName = that.closest('tr').find('td.chargeName').text().trim();
				var assigned = that.closest('tr').find('td.assignedTo');
				if(assigned.text().trim() !== '') {
					//console.log('assigned', assigned.text().trim());
					var msg = assigned.text().trim() +' will be vacated Are you sure?';
					console.log(msg);
					assigned.popover({
						content: msg,
						placement: 'left',
					});
					assigned.trigger('click');
				}
				$(this).hide();
				$(this).siblings('div.confirmButtons').show();
			});
			$('button.cancelRemove', row).click(function() {
				$(this).closest('div.confirmButtons').hide();
				$(this).closest('div.confirmButtons').siblings('button.removeButton').show();
			});
			$('button.confirmRemove', row).click(function() {
				var that = $(this);
				var chargeID = that.attr('data-id');
				var chargeName = that.closest('tr').find('td.chargeName').text().trim();
				
				$.ajax({
		 			headers: {
		 				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		 			},
		 			type: 'post',
		 			url: '{{ route('charge.assignments.destroy') }}',
		 			data: {
		 				charge: chargeID,
		 				committee: {{ $commID }}
		 			},
					success:  function(response) {
						console.log(response);
// 						$('#messageContainer').html('<div class="alert alert-success">'+ response.message +'</div>').fadeIn();
// 						$(row).addClass('added');
// 						that.closest('div.confirmButtons').hide().siblings('button.addedButton').show();
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
						<button type="button" class="btn btn-danger btn-sm removeButton" data-id="'+ data.charge +'">Remove</button>\
						<div class="confirmButtons" style="display: none;">\
							<button type="button" class="btn btn-danger btn-sm confirmRemove" data-id="'+ data.charge +'">Confirm</button>\
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
							<button type="button" class="btn btn-success btn-sm addedButton" style="display: none; opacity: 1;" disabled>Added</button>\
							<div class="confirmButtons" style="display: none">\
								<button type="button" class="btn btn-success btn-sm confirmButton" data-id="'+ data.id +'">Confirm</button>\
								<button type="button" class="btn btn-light btn-sm cancelButton">Cancel</button>\
							</div>\
						';
					}
					else {
						html = '<button type="button" class="btn btn-success btn-sm addedButton" style="opacity: 1;" disabled>Added</button>';
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