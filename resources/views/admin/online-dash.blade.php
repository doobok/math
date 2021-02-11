@extends('layouts.admin-vue')

@section('content')

<h1>Кімнати онлайн занять</h1>

<v-app>
  <video-rooms></video-rooms>
</v-app>

{{-- <v-app>
  <video-chat :user="{{ $user }}" :others="{{ $others }}" pusher-key="{{ config('broadcasting.connections.pusher.key') }}" pusher-cluster="{{ config('broadcasting.connections.pusher.options.cluster') }}"></video-chat>
</v-app> --}}

@endsection
