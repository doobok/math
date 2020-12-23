<!DOCTYPE html>
<html lang="uk">
	<head>
	  <meta charset="utf-8">
	  <meta http-equiv="X-UA-Compatible" content="IE=edge">
	  <meta name="viewport" content="width=device-width, initial-scale=1">
	  <meta name="csrf-token" content="{{ csrf_token() }}">
		<meta name="robots" content="noindex, nofollow">
		<title>Адмінпанель TutorMath</title>
	  <!--[if IE]><link rel="shortcut icon" href="path/to/favicon.ico"><![endif]-->
	  <link rel="shortcut icon" href="{{asset('favicon.ico')}}" type="image/x-icon" />
	  <link rel="apple-touch-icon" href="{{asset('apple-touch-icon.png')}}" />

		<link href="{{ mix('css/app.css')}}" rel="stylesheet">
	  <link href="{{ mix('css/dash.css')}}" rel="stylesheet">

	</head>
	<body>
		<div id="app">
			<div class="uk-navbar-container" uk-navbar>
			    <div class="uk-navbar-left uk-margin-left">
		        <a class="uk-navbar-toggle uk-text-large" href="#sidebar" uk-toggle>
							<i class="fas fa-bars"></i>
						</a>
			    </div>
					<div class="uk-navbar-right uk-margin-right">
						<a class="uk-logo uk-margin-small uk-margin-small-top" href="{{route('mainpage')}}" target="_blank">
							Tutor-Math
						</a>
			    </div>
			</div>
			<div class="uk-container uk-container-xlarge">

				@yield('content')

			</div>

			<!-- This is the off-canvas -->
	    <div id="sidebar" uk-offcanvas="mode: push">
        <div class="uk-offcanvas-bar uk-flex uk-flex-column">
          <button class="uk-offcanvas-close" type="button" uk-close></button>


	        <ul class="uk-nav uk-nav-primary uk-margin-auto-vertical">
						<li @if(str_contains(url()->current(), 'admin')) class="uk-active" @endif><a href="{{route('admin')}}"><i class="far fa-calendar-alt"></i> Розклад</a></li>
						@can ('admin')
							<li @if(str_contains(url()->current(), 'students')) class="uk-active" @endif><a href="{{route('students')}}"><i class="fas fa-user-graduate"></i> Учні</a></li>
							<li @if(str_contains(url()->current(), 'tutors')) class="uk-active" @endif><a href="{{route('tutors')}}"><i class="fas fa-chalkboard-teacher"></i> Тьютори</a></li>
							<li @if(str_contains(url()->current(), 'classrooms')) class="uk-active" @endif><a href="{{route('classrooms')}}"><i class="fas fa-puzzle-piece"></i> Кабінети</a></li>
							<li @if(str_contains(url()->current(), 'finance')) class="uk-active" @endif><a href="{{route('finance')}}"><i class="fas fa-dollar-sign"></i> Фінансова історія</a></li>
	            <li @if(str_contains(url()->current(), 'reports')) class="uk-active" @endif><a href="{{route('reports')}}"><i class="fas fa-clipboard-check"></i> Звіти</a></li>
						@endcan

            <li class="uk-nav-divider"></li>
						<li><a href="{{route('mainpage')}}"><i class="fas fa-home"></i> На головну</a></li>
						@can ('admin')
	            <li><a href="{{route('settings')}}"><i class="fas fa-cog"></i> Налаштування</a></li>
						@endcan
	        </ul>


        </div>
	    </div>

	</div>
		<!-- JS FILES -->
		<script src="{{ mix('js/dash.js') }}"></script>
		{{-- <script src="https://cdn.jsdelivr.net/npm/uikit@latest/dist/js/uikit.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/uikit@latest/dist/js/uikit-icons.min.js"></script> --}}
	</body>
</html>
