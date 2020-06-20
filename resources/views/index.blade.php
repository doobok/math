@extends('layouts.app')

@section('head')
  @component('components.meta')

    @slot('title') Репетитор по математике Буча | Цена занятий репетитора математики онлайн, ЗНО, ДПА, Скайп @endslot
    @slot('description')
      desc
    @endslot
    @slot('image') /bg-windows.jpg  @endslot
    @slot('date') 16.06.2020 @endslot

  @endcomponent
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
@endsection
