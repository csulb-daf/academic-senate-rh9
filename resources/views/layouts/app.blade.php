<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

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
	
	<!-- Fonts -->
	<link rel="dns-prefetch" href="//fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
	
	<!-- Styles -->
	<link href="{{ asset('css/app.css') }}" rel="stylesheet">
	<link href="{{ asset('css/styles.css') }}" rel="stylesheet">
	
	@stack('head')
</head>

<body>
	<div id="app">
		@include('partials.nav')
		
		<h1 class="pageHeader">Councils and Committees Management System</h1>
		<div class="container">
			<main class="py-4">
				<h2 style="font-weight: bold;">@yield('title')</h2>
				@yield('content')
			</main>
		</div>
	</div>
</body>
@stack('scripts')
</html>
