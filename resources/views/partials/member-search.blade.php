<select class="memberSelect form-control" name="memberSelect" id="memberSelect" aria-label="Select Member">
	<option></option>
</select>

<script>
$(document).ready(function() {
	var min = 3;
	
	$('.memberSelect').select2({
		width: '100%',
		minimumInputLength: min,
		placeholder: 'Select Member',
		sorter: data => data.sort((a, b) => a.text.localeCompare(b.text)),

		ajax: {
 			headers: {
 				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
 			},
 			type: 'post',
			url: "{{ route('member.list') }}",
			dataType: 'json',
      delay: 450,		//wait 450 milliseconds before triggering the request
			data: function (params) {
				return {
					q: params.term.replace(/[\W_]+/g, ' '), // search term(ignore non-alpha-numeric)
					min: min,
				};
			},
			processResults: function(data) {
				return {
					results: $.map(data, function(obj) {
						obj.name = (obj.campus_id == 0)? obj.name +' (CM)' : obj.name;
						return {
							id: obj.campus_id,
							text: obj.name,
						}
					})
				}
			}
		}		//ajax
	});		//select2
});
</script>