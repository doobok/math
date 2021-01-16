@extends('layouts.admin-vue')

@section('content')

<h1>Запрошення та користувачі</h1>

<v-app>
  <invites-table></invites-table>
  <br>
  <users-table></users-table>
</v-app>

@endsection
