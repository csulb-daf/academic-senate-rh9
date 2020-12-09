{{-- 
{{ dd($users) }}
{{ dd($ranks) }}
--}}
 
<select class="form-control" name="userSelect"  id="" >
	<option value="option_select" disabled selected>Select User</option>
	@foreach($users as $user)
		<option value="{{ $user['campus_id'] }}">{{ $user['lname'] }}, {{ $user['fname'] }}</option>
	@endforeach
</select>
