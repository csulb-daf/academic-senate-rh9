<select class="form-control  userSelect" name="userSelect"  id="userSelect" >
	<option></option>
</select>

<script>
$(document).ready(function() {
	var min = 3;
	var limit = 50;
	
	$('.userSelect').select2({
		width: '100%',
		minimumInputLength: min,
		placeholder: 'Select User',

		ajax: {
 			headers: {
 				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
 			},
 			type: 'post',
			url: "{{ route('employees.search') }}",
			dataType: 'json',
      delay: 450,		//wait 450 milliseconds before triggering the request
			data: function (params) {
				return {
					q: params.term.replace(/[^a-zA-Z0-9-]+/g, ' '), // search term(ignore non-alpha-numeric, don't ignore hyphen)
					min: min,
					page: params.page || 1,
					limit: limit,
				};
			},
			processResults: function(data, params) {
				params.page = params.page || 1;
				return {
					results: $.map(data.users, function(obj) {
						var displayName = (obj.campus_id == 0)? obj.name +' (CM)' : (obj.college_department !== '')? obj.name +' ('+ obj.college_department +')' : obj.name;
						var empType = (typeof obj.employeetype !== 'undefined')? obj.employeetype:'';
						return {
							id: obj.campus_id,
							text: displayName,
							originalName: obj.name,
							department: obj.department,
							college_department: obj.college_department,
							extension: obj.extension,
							email: obj.email,
							employeetype: empType,
						}
					}),
					pagination: {
						more: (params.page * limit) < data.count
					}
				}
			}	//processResults
		}		//ajax
	});		//select2
});
</script>