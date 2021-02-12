<select class="form-control  userSelect" name="userSelect"  id="userSelect" >
	<option></option>
</select>

<script>
$(document).ready(function() {
	var min = 3;
	
	$('.userSelect').select2({
		width: '100%',
		minimumInputLength: min,
		placeholder: 'Select User',
		sorter: data => data.sort((a, b) => a.text.localeCompare(b.text)),

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
					q: params.term.replace(/[\W_]+/g, ' '), // search term(ignore non-alpha-numeric)
					min: min,
				};
			},
			processResults: function(data) {
				console.log('data', data);
				
				return {
					results: $.map(data, function(obj) {
						//obj.name = (obj.campus_id == 0)? obj.name +' (CM)' : obj.name;
						return {
							id: obj.campus_id,
// 							id: obj.employeeid[0],
							text: obj.name,
// 							department: obj.department[0],
							//college_department: obj.college_department,
// 							college_department: obj.division[0],
							//extension: obj.extension,
// 							extension: obj.telephonenumber[0],
							//email: obj.email,
// 							email: obj.mail[0],
						}
					})
				}
			}
		}		//ajax
	});		//select2
});
</script>