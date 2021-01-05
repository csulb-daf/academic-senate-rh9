<select class="commSelect form-control js-example-basic-single" name="commSelect" id="commSelect">
	<option value="" disabled selected>Select Committee</option>
	@foreach($comms as $comm)
		<option value="{{ $comm->id }}">{{ $comm->committeename }}</option>
	@endforeach
</select>

<script>
$(document).ready(function() {
	$('.js-example-basic-single').select2();
});
</script>

