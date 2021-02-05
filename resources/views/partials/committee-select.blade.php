<select class="commSelect form-control" name="commSelect" id="commSelect" aria-label="Select Committee">
	<option></option>
<!-- 	<option value="" disabled selected>Select Committee</option> -->
	@foreach($comms as $comm)
		<option value="{{ $comm->id }}">{{ $comm->committeename }}</option>
	@endforeach
</select>

<script>
$(document).ready(function() {
	$('#commSelect').select2({
		minimumInputLength: 3,
		placeholder: 'Select Committee',
	});
});
</script>

