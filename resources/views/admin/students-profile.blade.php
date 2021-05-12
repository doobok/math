@extends('layouts.admin-vue')

@section('content')

<h1>Кабінет користувача</h1>

<v-app>
  <student-profile :student="{{$student}}"></student-profile>
</v-app>

@endsection
