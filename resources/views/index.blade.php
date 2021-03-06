@extends('layouts.app')

@section('head')
  @component('components.meta')

    @slot('title') @lang('site.title') @endslot
    @slot('description') {{__('site.description', [
      'price' => $options->get('price_2')->value
      ])}}
    @endslot
    @slot('image') /math_intro.webp @endslot
    @slot('date') {{$updated}} @endslot

  @endcomponent
  <link rel="alternate" href="https://bucha.tutor-math.com.ua" hreflang="ru-ua" />
  <link rel="alternate" href="https://bucha.tutor-math.com.ua/uk" hreflang="uk-ua" />
@endsection

@section('content')

  @include('layouts.mainpage.main-block')


  {{-- Кому полезно --}}
  @include('layouts.mainpage.who-useful-block')
  {{-- Почему выбирают нас --}}
  @include('layouts.mainpage.why-we-block')
  {{-- Выбор курса --}}
  @include('layouts.mainpage.courses-block')
  {{-- Знакомство --}}
  @include('layouts.mainpage.introduce-block')
  {{-- Знакомство --}}
  @include('layouts.mainpage.reviews-block')



  @include('layouts.mainpage.finish-block')
  {{-- modal form --}}
  @include('layouts.mainpage.modal')

  {{-- schema --}}
  @include('layouts.schema.company')
@endsection
