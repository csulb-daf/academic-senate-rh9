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
					q: params.term.replace(/[^a-zA-Z0-9-]+/g, ' '), // search term(ignore non-alpha-numeric, don't ignore hyphen)
					min: min,
				};
			},
			processResults: function(data) {
				return {
					results: $.map(data, function(obj) {
						var displayName = (obj.campus_id == 0)? obj.name +' (CM)' : (obj.college !== '')? obj.name +' ('+ obj.college +')' : obj.name;
						return {
							id: obj.campus_id,
							text: displayName,
							originalName: obj.name,
						}
					})
				}
			}
		}		//ajax
	});		//select2
});
</script>