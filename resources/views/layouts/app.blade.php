<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

	@include('layouts.partials.head')

	<body>

		<div id="app">

		@include('layouts.partials.topnav')

			@yield('content')

		@include('layouts.partials.footer')


		</div>

		<script src="{{ mix('js/app.js') }}"></script>

	</body>
</html>
