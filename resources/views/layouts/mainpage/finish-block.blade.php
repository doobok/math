<section class="uk-section uk-section-secondary">

  <div class="uk-container uk-container-large">
    <div class="uk-flex uk-flex-middle" uk-grid>
      {{-- large image --}}
      <div class="uk-width-1-3@m uk-visible@m">
          <img data-src="/math_end.png" alt="@lang('site.finish-img-alt')" uk-scrollspy="cls: uk-animation-slide-left-small; delay: 100" uk-img>
      </div>
      <div class="uk-width-2-3@m">
        <div class="uk-text-center uk-margin-medium-bottom">
          <span class="uk-heading-large uk-margin-small" uk-scrollspy="cls: uk-animation-fade"><mark>@lang('site.finish-mark')</mark></span>
          <h2 class="uk-heading-xlarge uk-margin-remove" uk-scrollspy="cls: uk-animation-fade; delay: 100">@lang('site.finish-h2')</h2>
        </div>

        {{-- small image --}}
        <div class="uk-width-1-3@m uk-text-center uk-hidden@m" uk-scrollspy="cls: uk-animation-fade; delay: 150">
            <img data-src="/math_end2.png" alt="@lang('site.finish-img-alt')" width="800px" uk-img>
        </div>

        <ul class="uk-list uk-list-bullet uk-margin-medium" style="font-weight: 500;" uk-scrollspy="cls: uk-animation-fade; delay: 200">
          <li>@lang('site.finish-list-1')</li>
          <li>@lang('site.finish-list-2')</li>
          <li>@lang('site.finish-list-3')</li>
          <li>@lang('site.finish-list-4')</li>
        </ul>

        <div class="uk-text-center uk-margin-medium-top" uk-scrollspy="cls: uk-animation-fade; delay: 250">

          <button-component
          title="@lang('site.finish-button')"
          clases="uk-width-1-1 uk-width-2-3@s"
          ></button-component>

        </div>

        {{-- promo countdown --}}
        @isset($promo_time)
          <p uk-scrollspy="cls: uk-animation-fade; delay: 500">@lang('site.finish-discount-1') *</p>
          <div class="uk-flex uk-flex-center" uk-scrollspy="cls: uk-animation-fade; delay: 600">
            @include('layouts.partials.countdown')
          </div>
          <p class="uk-text-meta" uk-scrollspy="cls: uk-animation-fade; delay: 650">*@lang('site.finish-discount-2')</p>
        @endisset

      </div>

    </div>
  </div>
</section>
