@extends('layouts.admin-vue')

@section('content')

<h1>Розклад занять</h1>

<v-app>
  <calendar-component :user="{{auth()->user()}}"></calendar-component>
</v-app>

@endsection
