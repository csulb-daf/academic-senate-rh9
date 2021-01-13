@extends('layouts.app')

@section('title', 'Home')

@section('content')

<div id="selectContainer">
	@include('partials.committee-select')
</div>

<h2 style="font-weight: bold;">Committee: <span id="tableTitle"></span></h2>
<table id="commSearch" class="display"></table>
<form  method="POST" id="memberSearch" action="{{ route('member.search', [], false) }}" style="display: none;">
	@csrf
	<input type="hidden" name="firstname" value="">
	<input type="hidden" name="lastname" value="">
	
	<div class="form-group">
		<label>Name Search</label>
		@include('partials.directory-search')
		<button type="submit" class="btn btn-primary btn-sm" disabled>Search</button>
	</div>
</form>
@endsection 

@section('scripts')
<script>
$(document).ready(function() {
	$('.userSearch').select2({
		width: '20%',
		matcher: matchCustom,
	});
	
	$('select#userSelect').change(function() {
		$('form#memberSearch').find('button').prop('disabled', false);
		var firstName = $('select#userSelect option:selected').data('firstname');
		var lastName = $('select#userSelect option:selected').data('lastname');
		$('form#memberSearch input[name=firstname]').val(firstName);
		$('form#memberSearch input[name=lastname]').val(lastName);
	});	
	
	$('#commSelect').on('change', function() {
		$('#tableTitle').text($(this).find('option:selected').text());
		table.ajax.reload();
	});

	var table = $('#commSearch').DataTable({
		autoWidth: false,
		dom: 'Blrtip',
		buttons: [{
			extend: 'pdf',
			text: 'Export to PDF', 
			className: 'btn btn-primary',
			title: function() {
				return $('#commSelect').find('option:selected').text();
			},
			orientation: 'landscape',
			exportOptions: {
				columns: 'th:not(.campusID)'
			}			
		}],		
		ajax: {
			url: 'comm-search',
			data: function(d) {
				d.cid = $('#commSelect').val();
			},
			dataSrc: '',
			error: function(xhr, error, thrown) {
				table.clear().draw();
			},
			complete: function() {
				$('form#memberSearch').insertAfter('#commSearch_wrapper .dataTables_length').show();
			}	
    },
		columns: [
			{ 
				title: 'Campus ID', className: 'campusID',
				render: function(data, type, row, meta) {
					if(row.campus_id == null) {
						var cid = $('#commSelect').val();
						var url = 	"{{ route('members.add', ['id'=>':id']) }}";
						url = url.replace(':id', cid);
						return '<a href="'+ url +'">VACANT</a>';
					}
					if(row.campus_id == 0) {
						return '<span class="badge badge-primary communityTag" style="color: #fff; font-size: 16px; margin-top: 10px;">CM</span>';
					}
					return row.campus_id;
				}	
			},
			{ title: 'Last Name', data: 'lastname' },
			{ title: 'First Name', data: 'firstname' },
			{ title: 'Rank', data: 'rank' },
			{ title: 'Department', data: 'department' },
			{ title: 'College', data: 'college' },
			{ title: 'Ext.', data: 'ext' },
			{ title: 'Email', data: 'email' },
			{ title: 'Term', data: 'term' },
			{ title: 'Charge Memberhip', data: 'charge' },
			{ title: 'Alternate', data: null, defaultContent: '',
				render: function(data, type, row) {
					return data.alternate == 1? 'Y':'';
				}			
			},
			{ title: 'Notes', data: 'notes' },
		],
	});
});

function matchCustom(params, data) {
  // If there are no search terms, return all of the data
  if ($.trim(params.term) === '') {
    return data;
  }

  /*** Custom Search ***/
	var searchData = data.text.toLowerCase().trim(),
	searchTerm = params.term.toLowerCase().trim(), 	//typed by user
	nameArray = searchData.split(', '),
	firstName = nameArray[1],
	lastName = nameArray[0];

	firstName = (typeof firstName ===  'undefined')? '':firstName.replace('(cm)', '').trim();
	lastName = (typeof lastName ===  'undefined')? '':lastName.trim();
	var fullName = firstName +' '+ lastName;
	var fullNameRev = lastName +' '+ firstName;

// 	console.log(firstName);
// 	console.log(lastName);
// 	console.log(searchData);
// 	console.log(searchTerm);

	if(firstName.indexOf(searchTerm)>-1) return data;
	if(lastName.indexOf(searchTerm)>-1) return data;
	if(fullName.indexOf(searchTerm)>-1) return data;
	if(fullNameRev.indexOf(searchTerm)>-1) return data;

  // Do not display the item if there is no 'text' property
  if (typeof data.text === 'undefined') {
    return null;
  }

  // `params.term` should be the term that is used for searching
  // `data.text` is the text that is displayed for the data object
  if (data.text.indexOf(params.term) > -1) {
    var modifiedData = $.extend({}, data, true);
    modifiedData.text += ' (matched)';

    // You can return modified objects from here
    // This includes matching the `children` how you want in nested data sets
    return modifiedData;
  }

  // Return `null` if the term should not be displayed
  return null;
}

</script>
@endsection