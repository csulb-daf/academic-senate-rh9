<select class="commSelect form-control commSelect" name="commSelect" id="commSelect" aria-label="Select Committee">
	<option></option>
</select>

<script>
$(document).ready(function() {
	var min = 3;
	
	$('.commSelect').select2({
		width: '100%',
		minimumInputLength: min,
		placeholder: 'Select Committee',

		ajax: {
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			type: 'post',
			url: "{{ route('committee.list') }}",
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
						return {
							id: obj.committee,
							text: obj.committeename,
						}
					})
				}
			}
		}		//ajax
	});		//select2
});
</script>