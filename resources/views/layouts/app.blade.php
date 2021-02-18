<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon" />	

	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">
	
	<title>
		@if (trim($__env->yieldContent('title')))
			@yield('title') 
		@else
			{{ config('app.name', 'Academic Senate') }}
		@endif
	</title>
	
	<!-- Scripts -->
	<script src="{{ asset('js/app.js') }}"></script>
	<script src="{{ asset('js/datatables.min.js') }}"></script>
	<script src="{{ asset('js/dataTables.responsive.min.js') }}"></script>
	<script src="{{ asset('js/dataTables.rowGroup.min.js') }}"></script>
	
	<script src="{{ asset('js/datatables-buttons/dataTables.buttons.min.js') }}"></script>
	<script src="{{ asset('js/datatables-buttons/jszip.min.js') }}"></script>
	<script src="{{ asset('js/datatables-buttons/pdfmake.min.js') }}"></script>
	<script src="{{ asset('js/datatables-buttons/buttons.print.min.js') }}"></script>
	<script src="{{ asset('js/datatables-buttons/vfs_fonts.js') }}"></script>
	<script src="{{ asset('js/datatables-buttons/buttons.html5.min.js') }}"></script>
	
	<script src="{{ asset('js/select2.min.js') }}"></script>
	<script src="{{ asset('js/actions.js') }}"></script>
	
	<!-- Fonts -->
	<link rel="dns-prefetch" href="//fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
	
	<!-- Styles -->
	<link href="{{asset('css/datatables.min.css')}}" rel="stylesheet">
	<link href="{{asset('css/responsive.dataTables.min.css')}}" rel="stylesheet">
	<link href="{{asset('css/rowGroup.dataTables.min.css')}}" rel="stylesheet">
	<link href="{{asset('css/select2.min.css')}}" rel="stylesheet">
	<link href="{{ asset('css/app.css') }}" rel="stylesheet">
	<link href="{{ asset('css/styles.css') }}" rel="stylesheet">
</head>

<body>
	<div id="app">
		@include('partials.nav')
		
		<h1 class="pageHeader">Councils and Committees Management System</h1>
		<div class="container">
			<main class="py-4">
				@yield('content')
			</main>
		</div>
	</div>
</body>
@yield('scripts')
</html>
