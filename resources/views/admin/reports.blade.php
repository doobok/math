@extends('layouts.admin-vue')

@section('content')

<h1>Аналітика</h1>


<v-app>
  <stats-graph></stats-graph>
  <reports-table></reports-table>
</v-app>

@endsection
