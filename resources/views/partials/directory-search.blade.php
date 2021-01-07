<select class="form-control  js-example-basic-single" name="userSelect"  id="userSelect" >
	<option value="" disabled selected>Select User</option>
	@foreach($users as $user)
		<option value="{{ $user->campus_id }}" class="{{ $user->campus_id == 0? 'community':'' }}" data-name="{{ $user->last_name }}, {{ $user->first_name }}">
			{{ $user->last_name }}, {{ $user->first_name }} {{ $user->campus_id == 0? '(CM)':'' }}
		</option>
	@endforeach
</select>
<span class="badge badge-primary communityTag" style="display: none; color: #fff; font-size: 16px; margin-top: 10px;">CM</span>

<script>
$(document).ready(function() {
	$('.js-example-basic-single').select2();

	$('select#userSelect').change(function() {
		var option = $('select#userSelect option:selected');
		if(option.hasClass('community')) {
			$('span.communityTag').show();
		}
		else {
			$('span.communityTag').hide();
		}
	});
});
</script>