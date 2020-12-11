<select class="commSelect form-control" style="margin: 20px 0;" name="commSelect"  id="commSelect">
	<option value="" disabled selected>Select Committee</option>
	<option value="0">Unassigned</option>
	@foreach($comms as $comm)
		<option value="{{ $comm->id }}">{{ $comm->committeename }}</option>
	@endforeach
</select>


