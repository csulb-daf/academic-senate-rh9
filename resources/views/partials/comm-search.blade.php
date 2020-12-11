<select class="commSelect form-control" name="commSelect"  id="commSelect">
	<option value="" disabled selected>Select Committee</option>
	<option value="0">Unassigned</option>
	@foreach($comms as $comm)
		<option value="{{ $comm->id }}" {{ old('commAssign') == $comm->id ? 'selected' : '' }}>{{ $comm->committeename }}</option>
	@endforeach
</select>
