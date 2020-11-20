@push('head')
	<link rel="stylesheet" href="{{asset('css/datatables.min.css')}}" type="text/css">
	
	<script src="{{asset('js/datatables.min.js')}}" defer></script>
	
@endpush

@extends('layouts.app')

@section('title', 'Committee Management')

@section('content')
<table id="committeeTable" class="display">
	<thead>
		<tr>
			<th>Column 1</th>
			<th>Column 2</th>
			<th>Column 3</th>
		</tr>
	</thead>

	<tr>
		<td>John</td>
		<td>Doe</td>
		<td>john@example.com</td>
	<tr>
	
	<tr>
		<td>Mary</td>
		<td>Moe</td>
		<td>mary@example.com</td>
	</tr>

	<tr>
		<td>July</td>
		<td>Dooley</td>
		<td>july@example.com</td>
	</tr>
</table>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
	$('#committeeTable').DataTable();
});
</script>
@endpush