<div class="uk-background-primary">
<div class="uk-container uk-container-large">

<nav class="uk-navbar-container uk-light uk-navbar-transparent uk-margin" uk-navbar>
    <div class="uk-navbar-left">

      @if (app()->getLocale() === 'uk')
        <a class="uk-logo uk-margin-small uk-margin-small-top" href="/uk">
          Tutor-Math
        </a>
      @else
        <a class="uk-logo uk-margin-small uk-margin-small-top" href="/">
          Tutor-Math
        </a>
      @endif
      </div>
      <div class="uk-navbar-center">
        <ul class="uk-navbar-nav">
          <li>
          @if (app()->getLocale() === 'uk')
            <a class="uk-margin-small" href="/">
              RUS
            </a>
          @else
            <a class="uk-margin-small" href="/uk">
              УКР
            </a>
          @endif
          </li>
        </ul>
      </div>
      <div class="uk-navbar-right">

        <a href="tel:+380673135525" class="uk-link-reset uk-text-large">
          <i class="fas fa-phone-square-alt"></i>
          <span class="uk-visible@s">
            +38 (067) 313-55-25
          </span>
        </a>

      </div>
    </nav>
  </div>
</div>
