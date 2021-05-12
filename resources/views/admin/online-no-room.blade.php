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

			<div uk-height-viewport class="uk-inline uk-light uk-width-1-1 uk-height-1-1" style="background-image: url(/title_apple.jpg);">
            <div class="uk-position-center uk-text-center">
								<h1>Заняття завершилось</h1>
								<p class="uk-text-bold">Наступне згідно розкладу</p>
								<a href="{{route('online')}}" class="uk-button uk-button-default">Актуальні заняття</a>
            </div>
        </div>

    </div>

    <script src="{{ mix('js/dash.js') }}"></script>
	</body>
</html>
