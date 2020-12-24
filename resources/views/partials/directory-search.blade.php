<select class="form-control  js-example-basic-single" name="userSelect"  id="userSelect" >
	<option value="" disabled selected>Select User</option>
	@foreach($users as $user)
		<option value="{{ $user->campus_id }}">{{ $user->last_name }}, {{ $user->first_name }}</option>
	@endforeach
</select>

<script>
$(document).ready(function() {
	$('.js-example-basic-single').select2();
});
</script>