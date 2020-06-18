<title>{{ $title }}</title>
<meta name="description" content="{{ $description }}">
@isset($robots)
<meta name="robots" content="{{$robots}}">
@else
<meta name="robots" content="index, follow">
@endisset
<meta name="author" content="{{config('app.name')}}">
<meta name="copyright" content="Copyright (c) 2020-{{ date("Y") }} by {{config('app.name')}}">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="@twitter_user">
<meta name="twitter:creator" content="@twitter_user">
<meta name="twitter:url" content="{{url()->current()}}">
<meta name="twitter:title" content="{{ $title }} - {{config('app.name')}}">
<meta name="twitter:description" content="{{ $description }}">
<meta name="twitter:image" content="{{ asset($image) }}">
<meta name="DC.title" content="{{ $title }} - {{config('app.name')}}">
<meta name="DC.creator" content="{{config('app.name')}}">
<meta name="DC.description" content="{{ $description }}">
<meta name="DC.publisher" content="{{config('app.name')}}">
<meta name="DC.contributor" content="{{config('app.name')}}">
<meta name="DC.date" content="{{ date('Y-m-d',strtotime($date))}}">
<meta name="DC.type" content="Text">
<meta name="DC.format" content="text/html">
<meta name="DC.identifier" content="{{url()->current()}}">
<meta name="DC.language" content="{{ str_replace('_', '-', app()->getLocale()) }}">
<meta name="DC.coverage" content="World">
<meta name="DC.rights" content="/policy">
<meta name="DC.rightsHolder" content="{{config('app.name')}}">
<meta property="og:url"           content="{{url()->current()}}">
<meta property="og:type"          content="website">
<meta property="og:title"         content="{{ $title }} - {{config('app.name')}}">
<meta property="og:description"   content="{{ $description }}">
<meta property="og:image"         content="{{ asset($image) }}">
{{-- <meta property="fb:app_id" content="698531037619095"> --}}
