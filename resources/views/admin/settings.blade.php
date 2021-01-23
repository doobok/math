@extends('layouts.admin-vue')

@section('content')

<h1>Налаштування</h1>

<v-app>
  <settings-table></settings-table>
  <hr>
  <telegram-webhook></telegram-webhook>
</v-app>
<p></p>
@endsection

{{-- @extends('layouts.admin')

@section('content')

<div class="uk-text-center uk-margin">
  <span class="uk-h2">Налаштування <a href="/" uk-icon="icon: forward"></a></span>
</div>

<!-- options -->
<div class="toggle-class">
  <p class="uk-text-small uk-margin-medium-bottom">при необхідності змініть налаштування і натисніть "OK" щоб зберегти</p>
  @foreach ($options as $option)
    <form method="POST" action="{{ route('updoption', $option->id) }}">
      @csrf
      {{ method_field('PATCH') }}
      <p class="uk-text-meta">{{$option->description}} ({{$option->name}})</p>
      <fieldset class="uk-fieldset">
        <div class="uk-margin-small uk-grid-small" uk-grid>
          <div class="uk-inline uk-width-expand">
            <input class="uk-input uk-border-pill" value="{{$option->value}}" id="email" type="text" name="value" required>
          </div>
          <div class="uk-width-auto">
            <button type="submit" class="uk-button uk-button-primary uk-border-pill">OK</button>
          </div>
        </div>

      </fieldset>
    </form>
  @endforeach

</div>


<!-- /options -->

<!-- new option -->
  <form class="toggle-class uk-width-large" method="POST" action="{{ route('addoption') }}" hidden>
    @csrf
    <div class="uk-text-center">
      <span class="uk-h4">Новий параметр</span>
    </div>
    <div class="uk-margin-small">
      <div class="uk-inline uk-width-1-1">
        <span class="uk-form-icon uk-form-icon-flip" data-uk-icon="icon: settings"></span>
        <input class="uk-input uk-border-pill" name="name" placeholder="Назва параметру (латиницею)*" required type="text">
      </div>
    </div>
    <div class="uk-margin-small">
      <div class="uk-inline uk-width-1-1">
        <span class="uk-form-icon uk-form-icon-flip" data-uk-icon="icon: code"></span>
        <input class="uk-input uk-border-pill" name="value" placeholder="Значення" required type="text">
      </div>
    </div>
    <div class="uk-margin-small">
      <div class="uk-inline uk-width-1-1">
        <span class="uk-form-icon uk-form-icon-flip" data-uk-icon="icon: info"></span>
        <input class="uk-input uk-border-pill" name="description" placeholder="Короткий опис" required type="text">
      </div>
    </div>
    <p class="uk-text-meta uk-text-center">Заповніть поля для нового параметру</p>
    <div class="uk-margin-bottom">
      <button type="submit" class="uk-button uk-button-primary uk-border-pill uk-width-1-1">Додати</button>
    </div>
  </form>
<!-- /new option -->

<!-- action buttons -->
<div>
  <div class="uk-text-center">
    <a class="uk-link-reset uk-text-small toggle-class" data-uk-toggle="target: .toggle-class ;animation: uk-animation-fade">Додати параметр</a>
    <a class="uk-link-reset uk-text-small toggle-class" data-uk-toggle="target: .toggle-class ;animation: uk-animation-fade" hidden><span data-uk-icon="arrow-left"></span> Скасувати</a>
  </div>
</div>
<!-- action buttons -->


@endsection --}}
