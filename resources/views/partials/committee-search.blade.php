<select class="commSelect form-control commSelect" name="commSelect" id="commSelect" aria-label="Select Committee">
	<option></option>
</select>

<script>
$(document).ready(function() {
	$('.commSelect').select2({
		width: '100%',
		minimumInputLength: 3,
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
					q: params.term // search term
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