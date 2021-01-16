@extends('layouts.admin-vue')

@section('content')

<v-app>
  <register-card invite="{{$invite}}"></register-card>
</v-app>

@endsection
