<!DOCTYPE html>
<html lang="uk">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
		<title>TutorMath - онлайн заняття</title>
		<link rel="icon" href="/favicon.ico">
    <link href="{{ mix('css/app.css')}}" rel="stylesheet">
	  <link href="{{ mix('css/dash.css')}}" rel="stylesheet">
	</head>
	<body>

    <div id="app">

      <v-app>
        <video-room :user="{{ $user }}" room_id="{{ $room_id }}" domain="{{config('app.jitsi_domain')}}"></video-room>
      </v-app>

    </div>

    <script src="{{ mix('js/dash.js') }}"></script>
	</body>
</html>
