<select class="commSelect form-control js-example-basic-single" name="commSelect" id="commSelect" aria-label="Select Committee">
	<option value="" disabled selected>Select Committee</option>
	@foreach($comms as $comm)
		<option value="{{ $comm->id }}">{{ $comm->committeename }}</option>
	@endforeach
</select>

<script>
$(document).ready(function() {
	$('.js-example-basic-single').select2({
		minimumInputLength: 3,
	});
});
</script>

