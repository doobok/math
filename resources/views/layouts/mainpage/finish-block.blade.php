<section class="uk-section uk-section-secondary">

  <div class="uk-container uk-container-large">
    <div class="uk-flex uk-flex-middle" uk-grid>
      {{-- large image --}}
      <div class="uk-width-1-3@m uk-visible@m">
          <img data-src="/tutor1.jpg" alt="@lang('site.finish-img-alt')" uk-img>
      </div>
      <div class="uk-width-2-3@m">
        <div class="uk-text-center uk-margin-medium-bottom">
          <span class="uk-heading-large uk-margin-small"><mark>@lang('site.finish-mark')</mark></span>
          <h2 class="uk-heading-xlarge uk-margin-remove">@lang('site.finish-h2')</h2>
        </div>

        {{-- small image --}}
        <div class="uk-width-1-3@m uk-text-center uk-hidden@m">
            <img data-src="/tutor1.jpg" alt="@lang('site.finish-img-alt')" uk-img>
        </div>

        <ul class="uk-list uk-list-bullet uk-margin-medium" style="font-weight: 500;">
          <li>@lang('site.finish-list-1')</li>
          <li>@lang('site.finish-list-2')</li>
          <li>@lang('site.finish-list-3')</li>
          <li>@lang('site.finish-list-4')</li>
        </ul>

        <div class="uk-text-center uk-margin-medium-top">

          <button-component
          title="@lang('site.finish-button')"
          clases="uk-width-1-1 uk-width-2-3@s"
          ></button-component>

        </div>

        <p>@lang('site.finish-discount-1') *</p>

        <div class="uk-flex uk-flex-center">

          @include('layouts.partials.countdown')

        </div>

        <p class="uk-text-meta">*@lang('site.finish-discount-2')</p>

      </div>

    </div>
  </div>
</section>
