@push('head')
@endpush

@extends('layouts.app')

@section('title', 'Committee Management')

@section('content')
<table id="sample" class="display">
	<thead>
		<tr>
			<th>Column 1</th>
			<th>Column 2</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>Row 1 Data 1</td>
			<td>Row 1 Data 2</td>
		</tr>
		<tr>
			<td>Row 2 Data 1</td>
			<td>Row 2 Data 2</td>
		</tr>
	</tbody>
</table>
@endsection 

@push('scripts')
<script>
	$(document).ready(function() {
		$('#sample').DataTable();
	});
</script>
@endpush