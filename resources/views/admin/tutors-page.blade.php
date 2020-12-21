@extends('layouts.admin-vue')

@section('content')

<h1>Тьютор {{$tutor->lname . ' ' . $tutor->name . ' ' . $tutor->mname}}</h1>

<ul class="uk-breadcrumb">
    <li><a href="{{route('tutors')}}"><i class="fas fa-arrow-left"></i> До списку тьюторів</a></li>
</ul>

<v-app>
  <tutor-page :tutor="{{$tutor}}"></tutor-page>
</v-app>

@endsection
