<select class="form-control  userSearch" name="userSelect"  id="userSelect" >
	<option value="" disabled selected>Select User</option>
	@foreach($users as $user)
		<option value="{{ $user->campus_id }}" class="{{ $user->campus_id == 0? 'community':'' }}" 
			data-name="{{ $user->last_name }}, {{ $user->first_name }}" data-firstname="{{ $user->first_name }}" data-lastname="{{ $user->last_name }}"
			data-department="{{ $user->department }}" data-college_department="{{ $user->college_department }}"  data-extension="{{ $user->extension }}"
			data-email="{{ $user->email }}">
			{{ $user->last_name }}, {{ $user->first_name }} {{ $user->campus_id == 0? '(CM)':'' }}
		</option>
	@endforeach
</select>

<script>
$(document).ready(function() {
	$('.userSearch').select2({
		matcher: matchCustom,
		minimumInputLength: 3,
	});
});
</script>