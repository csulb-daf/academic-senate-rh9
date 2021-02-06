<select class="form-control  userSearch" name="userSelect"  id="userSelect" >
	<option></option>
<!-- 	<option value="" disabled selected>Select User</option> -->
	{{-- @foreach($users as $user)
		<option value="{{ $user->campus_id }}" class="{{ $user->campus_id == 0? 'community':'' }}" 
			data-name="{{ $user->last_name }}, {{ $user->first_name }}" data-firstname="{{ $user->first_name }}" data-lastname="{{ $user->last_name }}"
			data-department="{{ $user->department }}" data-college_department="{{ $user->college_department }}"  data-extension="{{ $user->extension }}"
			data-email="{{ $user->email }}">
			{{ $user->last_name }}, {{ $user->first_name }} {{ $user->campus_id == 0? '(CM)':'' }}
		</option>
	@endforeach --}}
</select>

<script>
$(document).ready(function() {

	$('.userSearch').select2({
		//matcher: matchCustom,
		width: '100%',
		minimumInputLength: 3,
		placeholder: 'Select User',
		sorter: data => data.sort((a, b) => a.text.localeCompare(b.text)),

		ajax: {
 			headers: {
 				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
 			},
 			type: 'post',
			url: "{{ route('employees.search', [], false) }}",
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
							id: obj.campus_id,
							text: obj.name,
							department: obj.department,
							college_department: obj.college_department,
							extension: obj.extension,
							email: obj.email,
						}
					})
				}
			},

			
		}
	});
	
});
</script>