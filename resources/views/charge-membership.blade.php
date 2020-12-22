@extends('layouts.app')

@section('title', 'Charge Management')

@section('content')
<h2 style="font-weight: bold;">{{ $chargeName }}</h2>
<table id="chargeMembership" class="display"></table>

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
	<div class="input-group">
		<button class="btn btn-primary " type="submit">Add Committee</button>
<!-- 		<input class="form-control {{ $errors->has('committee')? 'is-invalid' : '' }}" type="text" name="committee" id="committee" value="" > -->
		@include('partials.committee-select') 
	</div>
</form>	

@endsection 

@section('scripts')
<script>
$(document).ready(function() {
	$('select.commSelect').change(function() {
		$(this).removeClass('is-invalid');
	});
	
	$('form#chargeForm').submit(function(e) {
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
				charge: {{ $id }},
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
	});
	
	var table = $('#chargeMembership').DataTable({
		paging: false,
		autoWidth: false,
    ajax: {
			url: "/charge/assignments/{{ $id }}/ajax",
// 			data: function(d) {
// 				d.id = $('#charge .commSelect').val();
// 			},
			
			dataSrc: '',
			error: function (xhr, error, thrown) {
				table.clear().draw();
			},
			
			complete: function() {
			}
    },
		columns: [
			{ title: 'Committee Name', data: 'commName' },
			
			{ title: 'Actions', data: null, defaultContent: '', width: '120px',
				render: function ( data, type, row ) {
					return getEditButtons(row.id);	    			
				}	
			}
		],
		
		columnDefs: [{		//Assignments column
			targets:  1,
			sortable: false,
		}],
		
	});	
	
});

function addChargeMem() {
	window.location = "{{ url('/charge/add') }}";
}

function assignCharge(id) {
	var url = 	"{{ route('charge.assignments', ['id'=>':id']) }}";
	url = url.replace(':id', id);
	window.location = url;
}
</script>
@endsection