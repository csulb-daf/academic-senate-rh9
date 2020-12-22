@extends('layouts.app')

@section('title', 'Charge Management')

@section('content')
<h2 style="font-weight: bold;">{{ $commName }}</h2>
<table id="chargeMembership" class="display"></table>
<table id="charges" class="display"></table>

<form method="POST" id="chargeForm" action="javascript:void(0);">
	@csrf
	<input type="hidden" name="tabName" value="rank">	

	@if ($errors->has('committee'))
		<div class="alert alert-danger">
			<ul>
				@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
	@endif
	
	@if(session()->has('committee'))
	    <div class="alert alert-success">
	        {{ session()->get('committee') }}
	    </div>
	@endif
	
	<div id="messageContainer" style="display: none;"></div>
<!-- 	<div class="input-group"> -->
<!-- 		<button class="btn btn-primary " type="submit">Add Committee</button> -->
<!-- 	</div> -->
</form>	

<button type="button" class="btn btn-light btn-sm addButton2" data-id="268">Add</button>\
@endsection 

@section('scripts')
<script>
$(document).ready(function() {
	$('select.commSelect').change(function() {
		$(this).removeClass('is-invalid');
	});
	
	/*$('form#chargeForm').submit(function(e) {
		e.preventDefault();
		var comm = $(this).find('select.commSelect').val();
		var commName = $(this).find('select.commSelect').find('option:selected').text();

		$.ajax({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			type: 'post',
			url: '{{ route('charge.assignments.add') }}',
			data: {
				charge: {{ $commID }},
				committee: comm,
			},

			success:  function(response) {
				$('select.commSelect').removeClass('is-invalid');
				$('#messageContainer').html('<div class="alert alert-success">'+ response.message +'</div>').fadeIn();
				
				var row = table.row.add({
					'commName': commName,
				}).draw(false).node(1);
				$(row).addClass('added');

			},
			error: function(err) {
				//console.log(err);
				if(err.status == 422) {
					$('select.commSelect').addClass('is-invalid');
					$('#messageContainer').html('<div class="alert alert-danger">'+ err.responseJSON.errors.committee +'</div>').fadeIn();

// 					$.each(err.responseJSON.errors, function (i, error) {
// 						console.log(error[0]);
// 					});	
				}
			},

		});
	});*/

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
					//return getEditButtons(row.id);

					var html='\
						<div class="editButtons">\
								<button type="button" class="btn btn-danger btn-sm removeButton" data-id="'+ data.charge +'">Remove</button>\
						</div>\
						<div class="delButtons" style="display: none;">\
							<button type="button" class="btn btn-danger btn-sm confirmDelete" >Confirm</button>\
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
    ajax: {
			url: "/charge/assignments/{{ $commID }}/charges/ajax",
			dataSrc: '',
			error: function (xhr, error, thrown) {
				table.clear().draw();
			},
			complete: function() {
				$('button.addButton').click(function() {
					var chargeID = $(this).attr('data-id');
					console.log(chargeID);
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
			 		});
				});
			}
    },
		columns: [
			{ title: 'Charge Name', data: 'charge' },
			{ title: 'Actions', data: null, defaultContent: '', width: '120px',
				render: function ( data, type, row ) {
					//return getEditButtons(row.id);
					var html='\
						<div class="editButtons">\
							<button type="button" class="btn btn-light btn-sm addButton" data-id="'+ data.id +'">Add</button>\
						</div>\
						<div class="delButtons" style="display: none;">\
							<button type="button" class="btn btn-success btn-sm addedButton">Added</button>\
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
	
});		//$(document).ready(function() {

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
	
</script>
@endsection