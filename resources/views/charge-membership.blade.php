@extends('layouts.app')

@section('title', 'Charge Management')

@section('content')
<h2 style="font-weight: bold;">{{ $chargeName }}</h2>
<table id="chargeAssign" class="display"></table>

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
	$('form#chargeForm').submit(function(e) {
		e.preventDefault();

		$.ajax({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			type: 'post',
			url: '{{ route('charge.assign.new') }}',
			data: {},

		});
	});
	
	var table = $('#chargeAssign').DataTable({
    ajax: {
			url: "/charge/membership/{{ $id }}/ajax",
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
			
			{ title: 'Assignments', data: null, defaultContent: '',
				render: function ( data, type, row ) {
					//console.log('id', data.id);
    			//return '<button type="button" class="btn btn-light border" onclick="javascrtipt:assignCharge('+ data.id +')">Edit</button>';

					var html='\
						<div class="editButtons">\
								<button type="button" class="btn btn-light btn-sm editButton">Edit</button>\
								<button type="button" class="btn btn-danger btn-sm deleteButton">Delete</button>\
								<img src="/images/check.svg" class="saved" style="width: 35px; display: none;">\
							</div>\
							<div class="delButtons" style="display: none;">\
									<button type="button" class="btn btn-danger btn-sm confirmDelete" data-id="'+ data.id +'">Confirm</button>\
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
	
});

function addChargeMem() {
	window.location = "{{ url('/charge/add') }}";
}

function assignCharge(id) {
	var url = 	"{{ route('charge.assign', ['id'=>':id']) }}";
	url = url.replace(':id', id);
	window.location = url;
}
</script>
@endsection