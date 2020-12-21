@extends('layouts.admin-vue')

@section('content')

<h1>Учень {{$student->concname}}</h1>

<ul class="uk-breadcrumb">
    <li><a href="{{route('students')}}"><i class="fas fa-arrow-left"></i> До списку учнів</a></li>
</ul>

<v-app>
  <student-page :student="{{$student}}"></student-page>
</v-app>

@endsection
