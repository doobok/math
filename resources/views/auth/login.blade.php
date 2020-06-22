@extends('layouts.admin')

@section('content')

<div class="uk-text-center uk-margin">
  <span class="uk-h3">Вхід</span>
</div>

<!-- login -->
<form class="toggle-class" method="POST" action="{{ route('login') }}">
  @csrf
  <fieldset class="uk-fieldset">
    <div class="uk-margin-small">
      <div class="uk-inline uk-width-1-1">
        <span class="uk-form-icon uk-form-icon-flip" data-uk-icon="icon: user"></span>
        <input class="uk-input uk-border-pill" placeholder="Username" id="email" type="email" name="email" required autocomplete="email" autofocus>
      </div>
    </div>
    <div class="uk-margin-small">
      <div class="uk-inline uk-width-1-1">
        <span class="uk-form-icon uk-form-icon-flip" data-uk-icon="icon: lock"></span>
        <input class="uk-input uk-border-pill" placeholder="Password" id="password" type="password" name="password" required>
      </div>
    </div>
    <div class="uk-margin-small">
      <label><input class="uk-checkbox" type="checkbox"> Keep me logged in</label>
    </div>
    <div class="uk-margin-bottom">
      <button type="submit" class="uk-button uk-button-primary uk-border-pill uk-width-1-1">LOG IN</button>
    </div>
  </fieldset>
</form>
<!-- /login -->

<!-- recover password -->
<form class="toggle-class" hidden>
  <div class="uk-margin-small">
    <div class="uk-inline uk-width-1-1">
      <span class="uk-form-icon uk-form-icon-flip" data-uk-icon="icon: mail"></span>
      <input class="uk-input uk-border-pill" placeholder="E-mail" required type="text">
    </div>
  </div>
  <div class="uk-margin-bottom">
    <button type="submit" class="uk-button uk-button-primary uk-border-pill uk-width-1-1">SEND PASSWORD</button>
  </div>
</form>
<!-- /recover password -->

<!-- action buttons -->
<div>
  <div class="uk-text-center">
    <a class="uk-link-reset uk-text-small toggle-class" data-uk-toggle="target: .toggle-class ;animation: uk-animation-fade">Forgot your password?</a>
    <a class="uk-link-reset uk-text-small toggle-class" data-uk-toggle="target: .toggle-class ;animation: uk-animation-fade" hidden><span data-uk-icon="arrow-left"></span> Back to Login</a>
  </div>
</div>
<!-- action buttons -->

@endsection
