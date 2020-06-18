<head>
   {{-- Global site tag (gtag.js) - Google Analytics --}}
   <script async src="https://www.googletagmanager.com/gtag/js?id={{config('app.gacode')}}"></script>
   <script>
     window.dataLayer = window.dataLayer || [];
     function gtag(){dataLayer.push(arguments);}
     gtag('js', new Date());

     gtag('config', '{{config('app.gacode')}}');
   </script>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  @yield('head')

  {{-- <!--[if IE]><link rel="shortcut icon" href="path/to/favicon.ico"><![endif]-->
  <link rel="shortcut icon" href="{{asset('favicon.ico')}}" type="image/x-icon" />
  <link rel="apple-touch-icon" href="{{asset('apple-touch-icon.png')}}" />
  <link rel="apple-touch-icon" sizes="57x57" href="{{asset('apple-touch-icon-57x57.png')}}" />
  <link rel="apple-touch-icon" sizes="72x72" href="{{asset('apple-touch-icon-72x72.png')}}" />
  <link rel="apple-touch-icon" sizes="76x76" href="{{asset('apple-touch-icon-76x76.png')}}" />
  <link rel="apple-touch-icon" sizes="114x114" href="{{asset('apple-touch-icon-114x114.png')}}" />
  <link rel="apple-touch-icon" sizes="120x120" href="{{asset('apple-touch-icon-120x120.png')}}" />
  <link rel="apple-touch-icon" sizes="144x144" href="{{asset('apple-touch-icon-144x144.png')}}" />
  <link rel="apple-touch-icon" sizes="152x152" href="{{asset('apple-touch-icon-152x152.png')}}" />
  <link rel="apple-touch-icon" sizes="180x180" href="{{asset('apple-touch-icon-180x180.png')}}" />
  <link rel="shortcut icon" sizes="180x180" href="{{asset('apple-touch-icon-180x180.png')}}" /> --}}

  <link href="{{ mix('css/app.css')}}" rel="stylesheet">



</head>
